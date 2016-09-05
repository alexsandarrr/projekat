<?php

class Admin_BooksController extends Zend_Controller_Action
{
	public function indexAction () {
            $flashMessenger = $this->getHelper('FlashMessenger');
        
            $systemMessages = array(
                'success' => $flashMessenger->getMessages('success'),
                'errors' => $flashMessenger->getMessages('errors'),
            );
            
            $cmsMembersDbTable = new Application_Model_DbTable_CmsMembers();
        
            $members = $cmsMembersDbTable->search(array(
    //            'filters' => array(
    //                'id' => array(1, 3, 5, 7)
    //            ),
                'orders' => array(
                    'order_number' => 'ASC'
                ),
                //'limit' => 4,
                //'page' => 3
            ));

            $this->view->members = $members;
            $this->view->systemMessages = $systemMessages;
	}
	
	public function addAction () {
		
	}
	
	public function editAction () {
		
	}
	
	public function deleteAction () {
		
	}
	
	public function enableAction () {
		
	}
	
	public function disableAction () {
		
	}
	
	public function updateorderAction () {
		
	}
}

