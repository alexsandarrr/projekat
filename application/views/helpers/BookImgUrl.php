<?php

class Zend_View_Helper_BookImgUrl extends Zend_View_Helper_Abstract
{
    public function bookImgUrl($book) {
        
        $bookImgFileName = $book['id'] . '.jpg';
        
        $bookImgFilePath = PUBLIC_PATH . "/uploads/books/" . $bookImgFileName;
        
        if (is_file($bookImgFilePath)) {
            return $this->view->baseUrl('/uploads/books/' . $bookImgFileName);
        } else {
            return $this->view->baseUrl('/uploads/books/no-image.jpg');
        }
    }
}

