<?php

class Admin_CoversController extends Zend_Controller_Action
{
    public function indexAction () {
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
                'success' => $flashMessenger->getMessages('success'),
                'errors' => $flashMessenger->getMessages('errors'),
        );

        $cmsCoversDbTable = new Application_Model_DbTable_CmsCovers();

        $covers = $cmsCoversDbTable->search(array(
//            'filters' => array(
//                'id' => array(1, 3, 5, 7)
//            ),
                'orders' => array(
                    'order_number' => 'ASC'
                ),
                //'limit' => 4,
                //'page' => 3
        ));

        $this->view->covers = $covers;
        $this->view->systemMessages = $systemMessages;
    }
	
    public function addAction () {
	$request = $this->getRequest();
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_CoverAdd();

        $form->populate(array(
            
        ));

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'save') {

            try {
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new cover');
                }

                $formData = $form->getValues();
                
                $cmsCoversTable = new Application_Model_DbTable_CmsCovers();
                
                $coverId = $cmsCoversTable->insertCover($formData);
                
                $flashMessenger->addMessage('Cover has been saved', 'success');

                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
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
            throw new Zend_Controller_Router_Exception('Invalid cover id: ' . $id, 404);
        }
        
        $cmsCoversTable = new Application_Model_DbTable_CmsCovers();
                
        $cover = $cmsCoversTable->getCoverById($id);
        
        if (empty($cover)) {
            throw new Zend_Controller_Router_Exception('No cover is found with id: ' . $id, 404);
        }
        
        
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_CoverAdd();

        //default form data
        $form->populate($cover);

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'update') {

            try {

                // check form is valid
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for cover');
                }

                // get form data
                $formData = $form->getValues();
                
                $cmsCoversTable->updateCover($cover['id'], $formData);

                // do actual task
                // save to database etc
                
                // set system message
                $flashMessenger->addMessage('Cover has been updated', 'success');

                // redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
        
        $this->view->cover = $cover;
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
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid cover id: ' . $id);
            
        }
        
        $cmsCoversTable = new Application_Model_DbTable_CmsCovers();
                
        $cover = $cmsCoversTable->getCoverById($id);
        
        if (empty($cover)) {
            
            throw new Application_Model_Exception_InvalidInput('No cover is found with id: ' . $id);
            
        }
        
        $cmsCoversTable->deleteCover($id);
        
        $flashMessenger->addMessage('Cover ' . $cover['first_name'] . ' ' . $cover['last_name'] . ' has been deleted', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
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
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid cover id: ' . $id);
            
        }
        
        $cmsCoversTable = new Application_Model_DbTable_CmsCovers();
                
        $cover = $cmsCoversTable->getCoverById($id);
        
        if (empty($cover)) {
            
            throw new Application_Model_Exception_InvalidInput('No cover is found with id: ' . $id);
            
        }
        
        $cmsCoversTable->disableCover($id);
        
        $flashMessenger->addMessage('Cover ' . $cover['first_name'] . ' ' . $cover['last_name'] . ' has been disabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
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
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid cover id: ' . $id);
            
        }
        
        $cmsCoversTable = new Application_Model_DbTable_CmsCovers();
                
        $cover = $cmsCoversTable->getCoverById($id);
        
        if (empty($cover)) {
            
            throw new Application_Model_Exception_InvalidInput('No cover is found with id: ' . $id);
            
        }
        
        $cmsCoversTable->enableCover($id);
        
        $flashMessenger->addMessage('Cover ' . $cover['first_name'] . ' ' . $cover['last_name'] . ' has been enabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
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
                            'controller' => 'admin_covers',
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
            
            $cmsCoversTable = new Application_Model_DbTable_CmsCovers();
            
            $cmsCoversTable->updateOrderOfCovers($sortedIds);
            
            $flashMessenger->addMessage('Order is successfully saved', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
            
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_covers',
                            'action' => 'index'
                                ), 'default', true);
        
        }
        
    }
}

