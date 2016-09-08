<?php

class Zend_View_Helper_CategoryUrl extends Zend_View_Helper_Abstract
{
    public function categoryUrl($category) {
        
        return $this->view->url(array(
            'id' => $category['id'],
            'category_slug' => $category['title']
            
        ), 'category-route', true);
        
    }
}

