<?php

class Admin_AuthorsController extends Zend_Controller_Action
{
    public function indexAction () {
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
                'success' => $flashMessenger->getMessages('success'),
                'errors' => $flashMessenger->getMessages('errors'),
        );

        $cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();

        $authors = $cmsAuthorsDbTable->search(array(
//            'filters' => array(
//                'id' => array(1, 3, 5, 7)
//            ),
                'orders' => array(
                    'order_number' => 'ASC'
                ),
                //'limit' => 4,
                //'page' => 3
        ));

        $this->view->authors = $authors;
        $this->view->systemMessages = $systemMessages;
    }
	
    public function addAction () {
	$request = $this->getRequest();
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_AuthorAdd();

        $form->populate(array(
            
        ));

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'save') {

            try {
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new author');
                }

                $formData = $form->getValues();
                
                unset($formData['author_photo']);
                
                $cmsAuthorsTable = new Application_Model_DbTable_CmsAuthors();
                
                $authorId = $cmsAuthorsTable->insertAuthor($formData);
                
                if ($form->getElement('author_photo')->isUploaded()) {
                    // photo is uploaded
                    
                    $fileInfos = $form->getElement('author_photo')->getFileInfo('author_photo');
                    $fileInfo = $fileInfos['author_photo'];
                    
                    try {
                        $authorPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);
                        
                        $authorPhoto->fit(303, 429);
                        
                        $authorPhoto->save(PUBLIC_PATH . '/uploads/authors/' . $authorId . '.jpg');
                        
                    } catch (Exception $ex) {
                        
                        $flashMessenger->addMessage('Author has been saved but error occured during image processing', 'errors');

                        $redirector = $this->getHelper('Redirector');
                        $redirector->setExit(true)
                                ->gotoRoute(array(
                                    'controller' => 'admin_authors',
                                    'action' => 'edit',
                                    'id' => $authorId
                                        ), 'default', true);
                    }
                }
                $flashMessenger->addMessage('Author has been saved', 'success');

                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
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
            
            throw new Zend_Controller_Router_Exception('Invalid author id: ' . $id, 404);
        }
        
        $cmsAuthorsTable = new Application_Model_DbTable_CmsAuthors();
                
        $author = $cmsAuthorsTable->getAuthorById($id);
        
        if (empty($author)) {
            throw new Zend_Controller_Router_Exception('No author is found with id: ' . $id, 404);
        }
        
        
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_AuthorAdd();

        $form->populate($author);

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'update') {

            try {

                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for author');
                }

                $formData = $form->getValues();
                
                unset($formData['author_photo']);
                
                if ($form->getElement('author_photo')->isUploaded()) {
                    // photo is uploaded
                    
                    $fileInfos = $form->getElement('author_photo')->getFileInfo('author_photo');
                    $fileInfo = $fileInfos['author_photo'];
                    
                    try {
						
                        $authorPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);
                        
                        $authorPhoto->fit(303, 429);
                        
                        $authorPhoto->save(PUBLIC_PATH . '/uploads/authors/' . $author['id'] . '.jpg');
                        
                    } catch (Exception $ex) {
                        
                        throw new Application_Model_Exception_InvalidInput('Error occured during image processing');
                        
                    }
                }
                
                $cmsAuthorsTable->updateAuthor($author['id'], $formData);

                $flashMessenger->addMessage('Author has been updated', 'success');

                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
        
        $this->view->author = $author;
	}
	
    public function deleteAction () {
        
        $request = $this->getRequest();
        
        if (!$request->isPost() || $request->getPost('task') != 'delete') {
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
			
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid author id: ' . $id);
            
        }
        
        $cmsAuthorsTable = new Application_Model_DbTable_CmsAuthors();
                
        $author = $cmsAuthorsTable->getAuthorById($id);
        
        if (empty($author)) {
            
            throw new Application_Model_Exception_InvalidInput('No author is found with id: ' . $id);
            
        }
        
        $cmsAuthorsTable->deleteAuthor($id);
        
        $flashMessenger->addMessage('Author ' . $author['first_name'] . ' ' . $author['last_name'] . ' has been deleted', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
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
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid author id: ' . $id);
            
        }
        
        $cmsAuthorsTable = new Application_Model_DbTable_CmsAuthors();
                
        $author = $cmsAuthorsTable->getAuthorById($id);
        
        if (empty($author)) {
            
            throw new Application_Model_Exception_InvalidInput('No author is found with id: ' . $id);
            
        }
        
        $cmsAuthorsTable->disableAuthor($id);
        
        $flashMessenger->addMessage('Author ' . $author['first_name'] . ' ' . $author['last_name'] . ' has been disabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
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
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid author id: ' . $id);
            
        }
        
        $cmsAuthorsTable = new Application_Model_DbTable_CmsAuthors();
                
        $author = $cmsAuthorsTable->getAuthorById($id);
        
        if (empty($author)) {
            
            throw new Application_Model_Exception_InvalidInput('No author is found with id: ' . $id);
            
        }
        
        $cmsAuthorsTable->enableAuthor($id);
        
        $flashMessenger->addMessage('Author ' . $author['first_name'] . ' ' . $author['last_name'] . ' has been enabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
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
                            'controller' => 'admin_authors',
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
            
            $cmsAuthorsTable = new Application_Model_DbTable_CmsAuthors();
            
            $cmsAuthorsTable->updateOrderOfAuthors($sortedIds);
            
            $flashMessenger->addMessage('Order is successfully saved', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
            
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_authors',
                            'action' => 'index'
                                ), 'default', true);
        
        }
        
    }
}

