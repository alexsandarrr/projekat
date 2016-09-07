<?php

class Zend_View_Helper_AuthorImgUrl extends Zend_View_Helper_Abstract
{
    public function authorImgUrl($author) {
        
        $authorImgFileName = $author['id'] . '.jpg';
        
        $authorImgFilePath = PUBLIC_PATH . "/uploads/authors/" . $authorImgFileName;
        
        if (is_file($authorImgFilePath)) {
            return $this->view->baseUrl('/uploads/authors/' . $authorImgFileName);
        } else {
            return $this->view->baseUrl('/uploads/authors/no-image.jpg');
        }
    }
}

