<?php

class PastebinController extends Zend_Controller_Action
{
    protected $_pastebinObj;
    protected $_config;

    public function init()
    {
        /* Initialize action controller here */
        $this->_pastebinObj = new Default_Model_Pastebin();
        $this->_config = Zend_Registry::get('config');
        $this->view->config = $this->_config;
    }

    public function indexAction()
    {
        // search form
        $request = $this->getRequest();
        $form    = new Default_Form_PastebinSearch($request);

        $search = array();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_getParam('reset')) {
                     $this->_redirect('/');
                }

                $formValues = $form->getValues();
                $search[$formValues['searchOn']] = $formValues['search'];
            }
        }

        $this->view->form = $form;

        // action body
        $paginator = $this->_pastebinObj->getPastebin($this->_getParam('id'), $search);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $this->view->paginator = $paginator;
        $this->view->plain = $this->_getParam('plain');
    }

    public function createAction()
    {
        $this->_forward('form');
    }

    public function editAction()
    {
        $this->_forward('form');
    }

    public function formAction()
    {
        // action body
        $defaultFormData = null;
        $shortId = $this->_getParam('id');
        if (!empty($shortId) && ($this->_config->site->allow->anonymousEdit || $this->_config->site->allow->ownEdit)) {
            if ($this->_config->site->allow->ownEdit && !$this->_config->site->allow->anonymousEdit) {
                $row = $this->_pastebinObj->findShortId($shortId);
                if ($row->ipAddress != $_SERVER['REMOTE_ADDR']) {
                    throw new Exception('You do not have permission to edit this');
                }
            }
            $defaultFormData = $this->_pastebinObj->findShortId($shortId);
        }

        $request = $this->getRequest();
        $form    = new Default_Form_Pastebin($defaultFormData);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $model = new Default_Model_Pastebin($form->getValues());
                $shortId = $model->save();
                $this->_redirect('/pastebin/index/id/'.$shortId);
            }
        }

        $this->view->form = $form;
    }

    public function deleteAction() {
        $deleteId = $this->_getParam('id');
        $row = $this->_pastebinObj->findShortId($deleteId);
        if ($this->_config->site->allow->anonymousDelete || ($this->_config->site->allow->ownDelete && ($row->ipAddress == $_SERVER['REMOTE_ADDR']))) {
            $this->_pastebinObj->delete($deleteId, 'short_id');
        } else {
            throw new Exception('You do not have permission to delete this');
        }

        $this->_redirect('/');
    }


}



