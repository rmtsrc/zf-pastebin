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
        // action body     
        $paginator = $this->_pastebinObj->getPastebin($this->_getParam('id'));
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
        if (!empty($shortId) && $this->_config->site->allowAnonymousEdit) {
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
        if ($this->_config->site->allowAnonymousDelete) {
            $this->_pastebinObj->delete($this->_getParam('id'), 'short_id');
        } else {
            throw new Exception('You do not have permission to delete');
        }

        $this->_redirect('/');
    }


}



