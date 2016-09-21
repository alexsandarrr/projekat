<?php

class Application_Form_Admin_CoverAdd extends Zend_Form
{
    public function init () {
		
        $title = new Zend_Form_Element_Text('title');
        $title->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('max' => 255))
                ->setRequired(true);
        $this->addElement($title);
    }

}

