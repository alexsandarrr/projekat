<?php

class Admin_ServicesController extends Zend_Controller_Action
{
    public function indexAction () {
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );
        
        $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
        
        $services = $cmsServicesDbTable->search(array(
            //'filters' => array(),
            'orders' => array(
                'order_number' => 'ASC'
            ),
            //'limit' => 4,
            //'page' => 3
        ));
        
        $this->view->services = $services;
        $this->view->systemMessages = $systemMessages;
    }
    
    public function addAction () {
        
        $request = $this->getRequest();
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_ServiceAdd();

        $form->populate(array(
            
        ));

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'saveservice') {

            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new service');
                }

                //get form data
                $formData = $form->getValues();
                
                $cmsServicesTable = new Application_Model_DbTable_CmsServices();
                
                $cmsServicesTable->insertService($formData);

                // do actual task
                //save to database etc
                
                
                
                //set system message
                $flashMessenger->addMessage('Service has been saved', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
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
            throw new Zend_Controller_Router_Exception('Invalid service id: ' . $id, 404);
        }
        
        $cmsServicesTable = new Application_Model_DbTable_CmsServices();
                
        $service = $cmsServicesTable->getServiceById($id);
        
        if (empty($service)) {
            throw new Zend_Controller_Router_Exception('No service is found with id: ' . $id, 404);
        }
        
        
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_ServiceAdd();

        //default form data
        $form->populate($service);

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'updateservice') {

            try {

                // check form is valid
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for service');
                }

                // get form data
                $formData = $form->getValues();
                
                // radimo update postojeceg zapisa u tabeli
                $cmsServicesTable->updateService($service['id'], $formData);

                // do actual task
                // save to database etc
                
                // set system message
                $flashMessenger->addMessage('Service has been updated', 'success');

                // redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
        
        $this->view->service = $service;
    }
    
    public function deleteAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'deleteservice') {
            // request is not post
            // or task is not delete
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid service id: ' . $id);
            
        }
        
        $cmsServicesTable = new Application_Model_DbTable_CmsServices();
                
        $service = $cmsServicesTable->getServiceById($id);
        
        if (empty($service)) {
            
            throw new Application_Model_Exception_InvalidInput('No service is found with id: ' . $id);
            
        }
        
        $cmsServicesTable->deleteService($id);
        
        $flashMessenger->addMessage('Service ' . $service['title'] . ' has been deleted', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        }
        
    }
    
    public function disableAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'disableservice') {
            // request is not post
            // or task is not delete
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid service id: ' . $id);
            
        }
        
        $cmsServicesTable = new Application_Model_DbTable_CmsServices();
                
        $service = $cmsServicesTable->getServiceById($id);
        
        if (empty($service)) {
            
            throw new Application_Model_Exception_InvalidInput('No service is found with id: ' . $id);
            
        }
        
        $cmsServicesTable->disableService($id);
        
        $flashMessenger->addMessage('Service ' . $service['title'] . ' has been disabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        }
        
    }

    public function enableAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'enableservice') {
            // request is not post
            // or task is not delete
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_service',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid service id: ' . $id);
            
        }
        
        $cmsServicesTable = new Application_Model_DbTable_CmsServices();
                
        $service = $cmsServicesTable->getServiceById($id);
        
        if (empty($service)) {
            
            throw new Application_Model_Exception_InvalidInput('No service is found with id: ' . $id);
            
        }
        
        $cmsServicesTable->enableService($id);
        
        $flashMessenger->addMessage('Service ' . $service['title'] . ' has been enabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        }
        
    }
    
    public function updateorderAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'saveOrderServices') {
            // request is not post
            // or task is not saveOrder
            // redirect to index page
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            
            $sortedIds = $request->getPost('sorted_ids_services');
            
            if(empty($sortedIds)) {
                throw new Application_Model_Exception_InvalidInput('Sorted ids are not sent');
            }
            
            $sortedIds = trim($sortedIds, ' ,');
            
            if (!preg_match('/^[0-9]+(,[0-9]+)*$/', $sortedIds)) {
                throw new Application_Model_Exception_InvalidInput('Invalid sorted ids: ' . $sortedIds);
            }
            
            $sortedIds = explode(',', $sortedIds);
            
            $cmsServicesTable = new Application_Model_DbTable_CmsServices();
            
            $cmsServicesTable->updateOrderOfServices($sortedIds);
            
            $flashMessenger->addMessage('Order is successfully saved', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
            
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_services',
                            'action' => 'index'
                                ), 'default', true);
        
        }
        
    }
    
    public function dashboardAction () {
        
        $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
        
        $servicesTotal = $cmsServicesDbTable->count(array(
            'orders' => array(
                'order_number' => 'ASC'
                )
        ));
        
        $servicesActive = $cmsServicesDbTable->count(array(
            'status' => Application_Model_DbTable_CmsServices::STATUS_ENABLED
        ));
        
        $this->view->servicesTotal = $servicesTotal;
        $this->view->servicesActive = $servicesActive;
        
    }
}

