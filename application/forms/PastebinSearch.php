<?php
// application/forms/PastebinSearch.php

class Default_Form_PastebinSearch extends Zend_Form
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

        $searchOptions = array(
            'name',
            'language',
            'code',
        );
        $searchArr = array();
        foreach ($searchOptions as $value) {
            $searchArr[$value] = ucfirst($value);
        }

        // Add the expires element
        $searchSelect = new Zend_Form_Element_Select('searchOn');
        $searchSelect->setLabel('Search On:')
            ->setRequired(true)
            ->addMultiOptions($searchArr)
            ->setValue((is_object($this->_defaultValues)) ? $this->_defaultValues->searchOn : 'name');

        $this->addElement($searchSelect);

        // Add an name element
        $this->addElement('text', 'search', array(
            'label'      => 'Search:',
            'filters'    => array('StringTrim'),
            'value'     => (is_object($this->_defaultValues)) ? $this->_defaultValues->search : '',
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Search',
        ));

        // Add the reset button
        $this->addElement('submit', 'reset', array(
            'ignore'   => true,
            'label'    => 'Reset',
        ));
    }
}
