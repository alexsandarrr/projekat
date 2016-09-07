<?php

class Application_Form_Admin_AuthorAdd extends Zend_Form
{
    public function init () {
		
        $firstName = new Zend_Form_Element_Text('name');
        $firstName->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('max' => 255))
                ->setRequired(true);
        $this->addElement($firstName);
        
        $about = new Zend_Form_Element_Textarea('about');
        $about->addFilter('StringTrim')
                ->setRequired(false);
        $this->addElement($about);
        
        $authorPhoto = new Zend_Form_Element_File('author_photo');
        $authorPhoto->addValidator('Count', true, 1)
                ->addValidator('MimeType', true, array('image/jpeg', 'image/gif', 'image/png'))
                ->addValidator('ImageSize', false, array(
                    'minwidth' => 303,
                    'minheight' => 429,
                    'maxwidth' => 2000,
                    'maxheight' => 2832
                ))
                ->addValidator('Size', false, array(
                    'max' => '10MB'
                ))
                ->setValueDisabled(true)
                ->setRequired(false);
        $this->addElement($authorPhoto);
    }

}

