<?php

class Zend_View_Helper_AuthorUrl extends Zend_View_Helper_Abstract
{
    public function authorUrl($author) {
        
        return $this->view->url(array(
            'id' => $author['id'],
            'author_slug' => $author['first_name'] . '-' . $author['last_name']
            
        ), 'author-route', true);
        
    }
}

