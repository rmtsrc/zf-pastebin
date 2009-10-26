<?php
// application/forms/Pastebin.php

class Default_Form_Pastebin extends Zend_Form
{
    protected $_defaultValues;

    public function  __construct($defaultValues = null)
    {
        $this->_defaultValues = $defaultValues;
        parent::__construct();
    }

    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an id element
        if (is_object($this->_defaultValues)) {
            $this->addElement('hidden', 'id', array(
                'value'     => $this->_defaultValues->id,
            ));

            $this->addElement('hidden', 'shortId', array(
                'value'     => $this->_defaultValues->shortId,
            ));
        }

        // Add an name element
        $this->addElement('text', 'name', array(
            'label'      => 'Your name:',
            'filters'    => array('StringTrim'),
            'value'     => (is_object($this->_defaultValues)) ? $this->_defaultValues->name : '',
        ));

        // Get list of languages
        if ($handle = opendir(realpath(APPLICATION_PATH . '/../library/geshi'))) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $lang = str_ireplace('.php', '', $file);
                    $languages[$lang] = $lang;
                }
            }
            closedir($handle);
        }

        // Add the language element
        $languageSelect = new Zend_Form_Element_Select('language');
        $languageSelect->setLabel('Language:')
            ->setRequired(true)
            ->addMultiOptions($languages)
            ->setValue((is_object($this->_defaultValues)) ? $this->_defaultValues->language : 'php');

        $this->addElement($languageSelect);

        // Add the code element
        $this->addElement('textarea', 'code', array(
            'label'      => 'Code:',
            'required'   => true,
            'class'     => 'codeInput',
            'value'     => (is_object($this->_defaultValues)) ? $this->_defaultValues->code : '',
            //'validators' => array(
            //    array('validator' => 'StringLength', 'options' => array(0, 20))
            //    )
        ));

        $expireOptions = array(
            'hour',
            'day',
            'month',
            'never',
        );
        $expireArr = array();
        foreach ($expireOptions as $value) {
            $expireArr[$value] = ucfirst($value);
        }

        // Add the expires element
        $expiresSelect = new Zend_Form_Element_Radio('expires');
        $expiresSelect->setLabel('Expires After:')
            ->setRequired(true)
            ->addMultiOptions($expireArr)
            ->setValue('month');

        $this->addElement($expiresSelect);

        // grab the config object        
        $config = Zend_Registry::get('config');

        if ($config->site->captcha) {
            // Add a captcha
            $this->addElement('captcha', 'captcha', array(
                'label'      => 'Please enter the 5 letters displayed below:',
                'required'   => true,
                'captcha'    => array(
                    'captcha' => 'Figlet',
                    'wordLen' => 5,
                    'timeout' => 300
                )
            ));
        }

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Save',
        ));

        if ($config->site->captcha) {
            // And finally add some CSRF protection
            $this->addElement('hash', 'csrf', array(
                'ignore' => true,
            ));
        }
    }
}
