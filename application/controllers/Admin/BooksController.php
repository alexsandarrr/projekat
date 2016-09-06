<?php

class Admin_BooksController extends Zend_Controller_Action
{
	public function indexAction () {
		$flashMessenger = $this->getHelper('FlashMessenger');

		$systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);

		$cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();

		$books = $cmsBooksDbTable->search(array(
//            'filters' => array(
//                'id' => array(1, 3, 5, 7)
//            ),
			'orders' => array(
				'order_number' => 'ASC'
			),
			//'limit' => 4,
			//'page' => 3
		));

		$this->view->books = $books;
		$this->view->systemMessages = $systemMessages;
	}
	
	public function addAction () {
		$request = $this->getRequest();
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_BookAdd();

        //default form data
        $form->populate(array(
            
        ));

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'save') {

            try {
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new book');
                }

                $formData = $form->getValues();
                
                unset($formData['book_photo']);
                
                $cmsBooksTable = new Application_Model_DbTable_CmsBooks();
                
                $bookId = $cmsBooksTable->insertBook($formData);
                
                if ($form->getElement('book_photo')->isUploaded()) {
                    // photo is uploaded
                    
                    $fileInfos = $form->getElement('book_photo')->getFileInfo('book_photo');
                    $fileInfo = $fileInfos['book_photo'];
                    
                    try {
                        $bookPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);
                        
                        $bookPhoto->fit(303, 429);
                        
                        $bookPhoto->save(PUBLIC_PATH . '/uploads/books/' . $bookId . '.jpg');
                        
                    } catch (Exception $ex) {
                        
                        $flashMessenger->addMessage('Book has been saved but error occured during image processing', 'errors');

                        $redirector = $this->getHelper('Redirector');
                        $redirector->setExit(true)
                                ->gotoRoute(array(
                                    'controller' => 'admin_books',
                                    'action' => 'edit',
                                    'id' => $bookId
                                        ), 'default', true);
                    }
                }
                $flashMessenger->addMessage('Book has been saved', 'success');

                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
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
            throw new Zend_Controller_Router_Exception('Invalid book id: ' . $id, 404);
        }
        
        $cmsBooksTable = new Application_Model_DbTable_CmsBooks();
                
        $book = $cmsBooksTable->getBookById($id);
        
        if (empty($book)) {
            throw new Zend_Controller_Router_Exception('No book is found with id: ' . $id, 404);
        }
        
        
        
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Admin_BookAdd();

        //default form data
        $form->populate($book);

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        if ($request->isPost() && $request->getPost('task') === 'update') {

            try {

                // check form is valid
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for book');
                }

                // get form data
                $formData = $form->getValues();
                
                unset($formData['book_photo']);
                
                if ($form->getElement('book_photo')->isUploaded()) {
                    // photo is uploaded
                    
                    $fileInfos = $form->getElement('book_photo')->getFileInfo('book_photo');
                    $fileInfo = $fileInfos['book_photo'];
                    
                    try {
                        // open uploaded photo in temporary directory
                        $bookPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);
                        
                        $bookPhoto->fit(303, 429);
                        
                        $bookPhoto->save(PUBLIC_PATH . '/uploads/books/' . $book['id'] . '.jpg');
                        
                    } catch (Exception $ex) {
                        
                        throw new Application_Model_Exception_InvalidInput('Error occured during image processing');
                        
                    }
                    
                    //$fileInfo = $_FILES['book_photo']; moze i ovako
                }
                
                // radimo update postojeceg zapisa u tabeli
                $cmsBooksTable->updateBook($book['id'], $formData);

                // do actual task
                // save to database etc
                
                // set system message
                $flashMessenger->addMessage('Book has been updated', 'success');

                // redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
        
        $this->view->book = $book;
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
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid book id: ' . $id);
            
        }
        
        $cmsBooksTable = new Application_Model_DbTable_CmsBooks();
                
        $book = $cmsBooksTable->getBookById($id);
        
        if (empty($book)) {
            
            throw new Application_Model_Exception_InvalidInput('No book is found with id: ' . $id);
            
        }
        
        $cmsBooksTable->deleteBook($id);
        
        $flashMessenger->addMessage('Book ' . $book['first_name'] . ' ' . $book['last_name'] . ' has been deleted', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
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
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid book id: ' . $id);
            
        }
        
        $cmsBooksTable = new Application_Model_DbTable_CmsBooks();
                
        $book = $cmsBooksTable->getBookById($id);
        
        if (empty($book)) {
            
            throw new Application_Model_Exception_InvalidInput('No book is found with id: ' . $id);
            
        }
        
        $cmsBooksTable->disableBook($id);
        
        $flashMessenger->addMessage('Book ' . $book['first_name'] . ' ' . $book['last_name'] . ' has been disabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
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
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try {
            // read $_POST['id]
        $id = (int) $request->getPost('id');
        
        if ($id <= 0) {
            
            throw new Application_Model_Exception_InvalidInput('Invalid book id: ' . $id);
            
        }
        
        $cmsBooksTable = new Application_Model_DbTable_CmsBooks();
                
        $book = $cmsBooksTable->getBookById($id);
        
        if (empty($book)) {
            
            throw new Application_Model_Exception_InvalidInput('No book is found with id: ' . $id);
            
        }
        
        $cmsBooksTable->enableBook($id);
        
        $flashMessenger->addMessage('Book ' . $book['first_name'] . ' ' . $book['last_name'] . ' has been enabled', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
        
    
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
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
                            'controller' => 'admin_books',
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
            
            $cmsBooksTable = new Application_Model_DbTable_CmsBooks();
            
            $cmsBooksTable->updateOrderOfBooks($sortedIds);
            
            $flashMessenger->addMessage('Order is successfully saved', 'success');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
            
        } catch (Application_Model_Exception_InvalidInput $ex) {
            
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_books',
                            'action' => 'index'
                                ), 'default', true);
        
        }
        
    }
}

