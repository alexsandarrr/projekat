<?php

class Zend_View_Helper_AuthorUrl extends Zend_View_Helper_Abstract
{
    public function authorUrl($author) {
        $slug = new Application_Model_Filter_UrlSlug();
        return $this->view->url(array(
            'id' => $author['id'],
            'author_slug' => $slug->filter($author['name'])
            
        ), 'author-route', true);
        
    }
}

