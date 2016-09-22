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
				print_r($formData);
				die();
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
	
	public function resetpassword() {
		
	}
}

