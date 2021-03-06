<?php

class Application_Form_Admin_BookAdd extends Zend_Form
{
    public function init () {
		
        $cmsSitemapPagesDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPageCategories = $cmsSitemapPagesDbTable->search(array(
                'filters' => array (
                        'short_title' => 'Book Categories'
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

        $cmsLanguagesDbTable = new Application_Model_DbTable_CmsLanguages();
        $languages = $cmsLanguagesDbTable->search();
		
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
		
        $sale = new Zend_Form_Element_Checkbox('sale');
        $sale->setRequired(false);
        $this->addElement($sale);

        $categoryId = new Zend_Form_Element_Select('category_id');
        $categoryId->addMultiOption('', '-- Select Category --')
        ->setRequired(true);
                
        $salePrice = new Zend_Form_Element_Text('sale_price');
        $salePrice->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        $this->addElement($salePrice);
		
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

        $languageId = new Zend_Form_Element_Select('language_id');
        $languageId->addMultiOption('', '-- Select Language --')
        ->setRequired(true);

        foreach ($languages as $language) {
                $languageId->addMultiOption($language['id'], $language['title']);
        }

        $this->addElement($languageId);
        
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

