<?php

class Admin_LanguagesController extends Zend_Controller_Action
{
    public function indexAction () {
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
                'success' => $flashMessenger->getMessages('success'),
                'errors' => $flashMessenger->getMessages('errors'),
        );

        $cmsLanguagesDbTable = new Application_Model_DbTable_CmsLanguages();

        $languages = $cmsLanguagesDbTable->search(array(
//            'filters' => array(
//                'id' => array(1, 3, 5, 7)
//            ),
                'orders' => array(
                    'order_number' => 'ASC'
                ),
                //'limit' => 4,
                //'page' => 3
        ));

        $this->view->languages = $languages;
        $this->view->systemMessages = $systemMessages;
    }
	
    public function addAction () {
	$request = $this->getRequest();
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_LanguageAdd();

        $form->populate(array(
            
        ));

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'save') {

            try {
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new language');
                }

                $formData = $form->getValues();
                
                $cmsLanguagesTable = new Application_Model_DbTable_CmsLanguages();
                
                $languageId = $cmsLanguagesTable->insertLanguage($formData);
                
                $flashMessenger->addMessage('Language has been saved', 'success');

                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
	}
	
	public function editAction () {
		$request = $this->getRequest();
        
        $id = (int) $request->getParam('id');
        
        if ($id <= 0) {
            
            // prekida se izvrsavanje programa i prikazuje se "Page not found"
            throw new Zend_Controller_Router_Exception('Invalid language id: ' . $id, 404);
        }
        
        $cmsLanguagesTable = new Application_Model_DbTable_CmsLanguages();
                
        $language = $cmsLanguagesTable->getLanguageById($id);
        
        if (empty($language)) {
            throw new Zend_Controller_Router_Exception('No language is found with id: ' . $id, 404);
        }
        
        
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_LanguageAdd();

        //default form data
        $form->populate($language);

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'update') {

            try {

                // check form is valid
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for language');
                }

                // get form data
                $formData = $form->getValues();
                
                // radimo update postojeceg zapisa u tabeli
                $cmsLanguagesTable->updateLanguage($language['id'], $formData);

                // do actual task
                // save to database etc
                
                // set system message
                $flashMessenger->addMessage('Language has been updated', 'success');

                // redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
        
        $this->view->language = $language;
	}
	
    public function deleteAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'delete') {
            // request is not post
            // or task is not delete
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid language id: ' . $id);
            
        }
        
        $cmsLanguagesTable = new Application_Model_DbTable_CmsLanguages();
                
        $language = $cmsLanguagesTable->getLanguageById($id);
        
        if (empty($language)) {
            
            throw new Application_Model_Exception_InvalidInput('No language is found with id: ' . $id);
            
        }
        
        $cmsLanguagesTable->deleteLanguage($id);
        
        $flashMessenger->addMessage('Language ' . $language['first_name'] . ' ' . $language['last_name'] . ' has been deleted', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        }
        
    }
    
    public function disableAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'disable') {
            // request is not post
            // or task is not delete
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid language id: ' . $id);
            
        }
        
        $cmsLanguagesTable = new Application_Model_DbTable_CmsLanguages();
                
        $language = $cmsLanguagesTable->getLanguageById($id);
        
        if (empty($language)) {
            
            throw new Application_Model_Exception_InvalidInput('No language is found with id: ' . $id);
            
        }
        
        $cmsLanguagesTable->disableLanguage($id);
        
        $flashMessenger->addMessage('Language ' . $language['first_name'] . ' ' . $language['last_name'] . ' has been disabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        }
        
    }

    public function enableAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'enable') {
            // request is not post
            // or task is not delete
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid language id: ' . $id);
            
        }
        
        $cmsLanguagesTable = new Application_Model_DbTable_CmsLanguages();
                
        $language = $cmsLanguagesTable->getLanguageById($id);
        
        if (empty($language)) {
            
            throw new Application_Model_Exception_InvalidInput('No language is found with id: ' . $id);
            
        }
        
        $cmsLanguagesTable->enableLanguage($id);
        
        $flashMessenger->addMessage('Language ' . $language['first_name'] . ' ' . $language['last_name'] . ' has been enabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        }
        
    }
    
    public function updateorderAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'saveOrder') {
            // request is not post
            // or task is not saveOrder
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            
            $sortedIds = $request->getPost('sorted_ids');
            
            if(empty($sortedIds)) {
                throw new Application_Model_Exception_InvalidInput('Sortered ids are not sent');
            }
            
            $sortedIds = trim($sortedIds, ' ,');
            
            if (!preg_match('/^[0-9]+(,[0-9]+)*$/', $sortedIds)) {
                throw new Application_Model_Exception_InvalidInput('Invalid sorted ids: ' . $sortedIds);
            }
            
            $sortedIds = explode(',', $sortedIds);
            
            $cmsLanguagesTable = new Application_Model_DbTable_CmsLanguages();
            
            $cmsLanguagesTable->updateOrderOfLanguages($sortedIds);
            
            $flashMessenger->addMessage('Order is successfully saved', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
            
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_languages',
                            'action' => 'index'
                                ), 'default', true);
        
        }
        
    }
}

