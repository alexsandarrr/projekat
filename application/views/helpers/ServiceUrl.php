<?php

class Zend_View_Helper_ServiceUrl extends Zend_View_Helper_Abstract
{
    public function serviceUrl($service) {
        $slug = new Application_Model_Filter_UrlSlug();
        return $this->view->url(array(
            'id' => $service['id'],
            'service_slug' => $slug->filter($service['title'])
            
        ), 'service-route', true);
        
    }
}

