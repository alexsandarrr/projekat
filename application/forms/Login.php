<?php

class Application_Form_Login extends Zend_Form
{
	public function init() {
		
		$email = new Zend_Form_Element_Text('email');
		$email->addFilter('StringTrim')
			->addValidator('EmailAddress', false, array('domain' => false))
			->setRequired(false);
		$this->addElement($email);
		
		$password = new Zend_Form_Element_Password('password');
		$password->setRequired(true);
		$this->addElement($password);
	}
}