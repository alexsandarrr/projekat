<?php

class FrontuserController extends Zend_Controller_Action
{
	public function indexAction() {
		
	}

	public function registerAction() {
		$request = $this->getRequest();

		$flashMessenger = $this->getHelper('FlashMessenger');

		$systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
		
		$form = new Application_Form_Register();
		
		if ($request->isPost() && $request->getPost('task') === 'register') {
			try {
				$form->populate($request->getPost());
				$formData = $form->getValues();
				
				unset($formData['password_confirm']);
				
				$cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();

				$cmsUsersTable->insertFrontUser($formData);

				$flashMessenger->addMessage('User has been saved', 'success');

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

		$this->view->systemMessages = $systemMessages;
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

				//set system message
				$flashMessenger->addMessage('User has been updated', 'success');

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

