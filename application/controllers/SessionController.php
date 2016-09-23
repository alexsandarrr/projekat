<?php

class SessionController extends Zend_Controller_Action
{
	public function loginAction() {
		$loginForm = new Application_Form_Login();
		
		$request = $this->getRequest();
		$request instanceof Zend_Controller_Request_Http;
		
		if ($request->isPost() && $request->getPost('task') === 'login') {
			
			if ($loginForm->isValid($request->getPost())) {
				
				$authAdapter = new Zend_Auth_Adapter_DbTable();
				$authAdapter->setTableName('cms_front_users')
					->setIdentityColumn('email')
					->setCredentialColumn('password')
					->setCredentialTreatment('MD5(?) AND status != 0');
				
				$authAdapter->setIdentity($loginForm->getValue('email'));
				$authAdapter->setCredential($loginForm->getValue('password'));
				
				$auth = Zend_Auth::getInstance();
				
				$result = $auth->authenticate($authAdapter);
				
				if ($result->isValid()) {
					
					// Smestanje kompletnog reda iz tabele cms_users kao identifikator da je korisnik ulogovan
					// Po defaultu se semsta samo username, a ovako smestamo asocijativni niz tj row iz tabele
					// Asocijativni niz $user ima kljuceve koji su u stvari nazivi kolona u tabeli cms_users
					$user = (array) $authAdapter->getResultRowObject();
					$auth->getStorage()->write($user);
					
					$redirector = $this->getHelper('Redirector');
					$redirector instanceof Zend_Controller_Action_Helper_Redirector;

					$redirector->setExit(true)
						->gotoRoute(array(
							'controller' => 'index',
							'action' => 'index'
						), 'default', true);
				}
				
			} 
		}
		
	}
	
	public function logoutAction() {
		
		$auth = Zend_Auth::getInstance();
		// Brise indikator da je neko ulogovan
		$auth->clearIdentity();
		
		$redirector = $this->getHelper('Redirector');
		$redirector instanceof Zend_Controller_Action_Helper_Redirector;
		$redirector->setExit(true)
			->gotoRoute(array(
				'controller' => 'session',
				'action' => 'login'
			), 'default', true);
	}
}


