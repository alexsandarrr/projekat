<?php

class Application_Form_Admin_BookAdd extends Zend_Form
{
    public function init () {
		
		$cmsSitemapPagesDbTable = new Application_Model_DbTable_CmsSitemapPages();
		$sitemapPageCategories = $cmsSitemapPagesDbTable->search(array(
			'filters' => array (
				'short_title' => 'Categories'
			)
		));
		$categoriesId = $sitemapPageCategories[0]['id'];
		$categories = $cmsSitemapPagesDbTable->search(array(
			'filters' => array (
				'parent_id' => $categoriesId
			)
		));
		
		$cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();
		$authors = $cmsAuthorsDbTable->search();
		
		$cmsCoversDbTable = new Application_Model_DbTable_CmsCovers();
		$covers = $cmsCoversDbTable->search();
		
		$cmsLettersDbTable = new Application_Model_DbTable_CmsLetters();
		$letters = $cmsLettersDbTable->search();
		
//		print_r($authors);
//		die();
		
        $title = new Zend_Form_Element_Text('title');
        $title->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('max' => 255))
                ->setRequired(true);
        $this->addElement($title);
        
        $format = new Zend_Form_Element_Text('format');
        $format->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        $this->addElement($format);
        
        $numberOfPages = new Zend_Form_Element_Text('number_of_pages');
        $numberOfPages->addFilter('StringTrim')
                ->setRequired(true);
        $this->addElement($numberOfPages);
        
        $dateOfPublication = new Zend_Form_Element_Text('date_of_publication');
        $dateOfPublication->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(false);
        $this->addElement($dateOfPublication);
        
        $about = new Zend_Form_Element_Textarea('about');
        $about->addFilter('StringTrim')
                ->setRequired(false);
        $this->addElement($about);
		
		$isbn = new Zend_Form_Element_Text('isbn');
        $isbn->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        $this->addElement($isbn);
		
		$price = new Zend_Form_Element_Text('price');
        $price->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        $this->addElement($price);
		
		$categoryId = new Zend_Form_Element_Select('category_id');
		$categoryId->addMultiOption('', '-- Select Category --')
                ->setRequired(true);
		
		foreach ($categories as $category) {
			$categoryId->addMultiOption($category['id'], $category['short_title']);
		}
		
		$this->addElement($categoryId);
		
		$authorId = new Zend_Form_Element_Select('author_id');
		$authorId->addMultiOption('', '-- Select Author --')
                ->setRequired(true);
		
		foreach ($authors as $author) {
			$authorId->addMultiOption($author['id'], $author['name']);
		}
		
		$this->addElement($authorId);
		
		$coverId = new Zend_Form_Element_Select('cover_id');
		$coverId->addMultiOption('', '-- Select Cover --')
                ->setRequired(true);
		
		foreach ($covers as $cover) {
			$coverId->addMultiOption($cover['id'], $cover['title']);
		}
		
		$this->addElement($coverId);
		
		$letterId = new Zend_Form_Element_Select('letter_id');
		$letterId->addMultiOption('', '-- Select Letter --')
                ->setRequired(true);
		
		foreach ($letters as $letter) {
			$letterId->addMultiOption($letter['id'], $letter['title']);
		}
		
		$this->addElement($letterId);
        
        $bookPhoto = new Zend_Form_Element_File('book_photo');
        $bookPhoto->addValidator('Count', true, 1)
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
        $this->addElement($bookPhoto);
    }

}

