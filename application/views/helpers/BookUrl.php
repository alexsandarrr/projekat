<?php

class Zend_View_Helper_BookUrl extends Zend_View_Helper_Abstract
{
    public function bookUrl($book) {
        
        return $this->view->url(array(
            'id' => $book['id'],
            'book_slug' => $book['title']
            
        ), 'book-route', true);
        
    }
}

