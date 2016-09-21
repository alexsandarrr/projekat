<?php

class Zend_View_Helper_BookUrl extends Zend_View_Helper_Abstract
{
    public function bookUrl($book) {
        $slug = new Application_Model_Filter_UrlSlug();
        return $this->view->url(array(
            'id' => $book['id'],
            'book_slug' => $slug->filter($book['title'])
            
        ), 'book-route', true);
        
    }
}

