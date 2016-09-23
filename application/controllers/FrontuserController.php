<?php

class FrontuserController extends Zend_Controller_Action
{
	public function indexAction() {
		$request = $this->getRequest();
		
		$cmsFrontUsersDbTable = new Application_Model_DbTable_CmsFrontUsers();
		
		$loggedInUser = Zend_Auth::getInstance()->getIdentity();
		
		if (!empty($loggedInUser)){
			$frontUsers = $cmsFrontUsersDbTable->search(array(
				'filters' => array(
					'id' => $loggedInUser['id'],
					'status' => Application_Model_DbTable_CmsFrontUsers::STATUS_ENABLED,
					),
				'orders' => array(
					'order_number' => 'ASC'
				),
			));
			
			$frontUser = $frontUsers[0];
			
			$user = $cmsFrontUsersDbTable->getFrontUserById($loggedInUser['id']);
			
			$form = new Application_Form_EditFrontUser();
			
			$form->populate($user);
			
			if ($request->isPost() && $request->getPost('task') === 'update') {
				$form->populate($request->getPost());
				$formData = $form->getValues();
				
				unset($formData['password']);
				
				$cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();
				$cmsUsersTable->updateFrontUser($user['id'], $formData);

				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'frontuser',
						'action' => 'index'
						), 'default', true);
			}
			
			$formPassword = new Application_Form_ChangePassword();
			
			if ($request->isPost() && $request->getPost('task') === 'newpassword') {
				$formPassword->populate($request->getPost());
				$formData = $formPassword->getValues();
				
				unset($formData['password_confirm']);
				
				$cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();
				$cmsUsersTable->changeFrontUserPassword($user['id'], $formData['password']);

				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'frontuser',
						'action' => 'index'
						), 'default', true);
			}
			
		} else {
			$redirector = $this->getHelper('Redirector');
			$redirector instanceof Zend_Controller_Action_Helper_Redirector;
			$redirector->setExit(true)
				->gotoRoute(array(
					'controller' => 'session',
					'action' => 'login'
				), 'default', true);
		}
		
		$this->view->form = $form;
		$this->view->frontUser = $frontUser;
	}

	public function registerAction() {
		$request = $this->getRequest();
		
		$form = new Application_Form_Register();
		
		if ($request->isPost() && $request->getPost('task') === 'register') {
			try {
				$form->populate($request->getPost());
				$formData = $form->getValues();
				
				unset($formData['password_confirm']);
				
				$cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();

				$cmsUsersTable->insertFrontUser($formData);

				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'index',
						'action' => 'index'
						), 'default', true);
			} catch (Application_Model_Exception_InvalidInput $ex) {
				$systemMessages['errors'][] = $ex->getMessage();
			}
		}

		$this->view->form = $form;
	}
	
	public function resetpasswordAction() {
		
	}
	
	public function editAction() {
		$request = $this->getRequest();

		$id = (int) $request->getParam('id');

		if ($id <= 0) {
			throw new Zend_Controller_Router_Exception('Invalid user id: ' . $id, 404);
		}

		$loggedInUser = Zend_Auth::getInstance()->getIdentity();

		if ($id == $loggedInUser['id']) {
			//redirect user to edit profile page
			$redirector = $this->getHelper('Redirector');
			$redirector->setExit(true)
				->gotoRoute(array(
					'controller' => 'frontuser',
					'action' => 'edit'
					), 'default', true);
		}

		$cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();

		$user = $cmsUsersTable->getFrontUserById($id);

		if (empty($user)) {
			throw new Zend_Controller_Router_Exception('No user is found with id: ' . $id, 404);
		}

		$form = new Application_Form_Register($user['id']);

		$form->populate($user);

		if ($request->isPost() && $request->getPost('task') === 'update') {

			try {

				//check form is valid
				if (!$form->isValid($request->getPost())) {
					throw new Application_Model_Exception_InvalidInput('Invalid data was sent for user');
				}

				//get form data
				$formData = $form->getValues();

				$cmsUsersTable->updateUser($user['id'], $formData);

				//redirect to same or another page
				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'admin_users',
						'action' => 'index'
						), 'default', true);
			} catch (Application_Model_Exception_InvalidInput $ex) {
				$systemMessages['errors'][] = $ex->getMessage();
			}
		}

		$this->view->systemMessages = $systemMessages;
		$this->view->form = $form;

		$this->view->user = $user;
	}
}

