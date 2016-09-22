<?php

class Application_Form_Register extends Zend_Form
{
	public function init () {
		$firstName = new Zend_Form_Element_Text('first_name');
        $firstName->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 2, 'max' => 255))
                ->setRequired(true);
        $this->addElement($firstName);
		
		$lastName = new Zend_Form_Element_Text('last_name');
        $lastName->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 2, 'max' => 255))
                ->setRequired(true);
        $this->addElement($lastName);
		
		$address = new Zend_Form_Element_Text('address');
        $address->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 2, 'max' => 255))
                ->setRequired(true);
        $this->addElement($address);
		
		$city = new Zend_Form_Element_Text('city');
        $city->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 2, 'max' => 255))
                ->setRequired(true);
        $this->addElement($city);
		
		$mobilePhone = new Zend_Form_Element_Text('mobile_phone');
        $mobilePhone->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 2, 'max' => 255))
                ->setRequired(true);
        $this->addElement($mobilePhone);
		
		$email = new Zend_Form_Element_Text('email');
		$email->addFilter('StringTrim')
			->addValidator('EmailAddress', false, array('domain' => false))
			->setRequired(false);
		$this->addElement($email);
		
		$password = new Zend_Form_Element_Password('password');
		$password->addValidator('StringLength', false, array('min' => 5, 'max' => 255))
			->setRequired(true);
		$this->addElement($password);
		
		$passwordConfirm = new Zend_Form_Element_Password('password_confirm');
		$passwordConfirm->addValidator('Identical', false, array(
			'token' => 'password',
			'messages' => array(
				Zend_Validate_Identical::NOT_SAME => 'Passwords do not match'
			)
		))->setRequired(true);
		$this->addElement($passwordConfirm);
		
		$newsletter = new Zend_Form_Element_Checkbox('newsletter');
		$newsletter->setRequired(false);
		$this->addElement($newsletter);
		
		$termsAndConditions = new Zend_Form_Element_Checkbox('terms_and_conditions');
		$termsAndConditions->setRequired(true);
		$this->addElement($termsAndConditions);
		
		$personalData = new Zend_Form_Element_Checkbox('personal_data');
		$personalData->setRequired(true);
		$this->addElement($personalData);
	}
}

