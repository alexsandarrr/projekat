<?php

class AuthorsController extends Zend_Controller_Action
{
    public function indexAction () {
        $request = $this->getRequest();
        $sitemapPageId = (int) $request->getParam('sitemap_page_id');
        if ($sitemapPageId <= 0) {
            throw new Zend_Controller_Router_Exception('Invalid sitemap page id: ' . $sitemapPageId, 404);
        }
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
        if (!$sitemapPage) {
            throw new Zend_Controller_Router_Exception('No sitemap page is found for id: ' . $sitemapPageId, 404);
        }
        if (
            $sitemapPage['status'] == Application_Model_DbTable_CmsSitemapPages::STATUS_DISABLED &&
            !Zend_Auth::getInstance()->hasIdentity()
        ) {
            throw new Zend_Controller_Router_Exception('Sitemap page is disabled', 404);
        }
		
		$cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();
        $authors = $cmsAuthorsDbTable->search(array(
           'orders' => array(
               'order_number' => 'ASC',
           ),
        ));
		
        $this->view->sitemapPage = $sitemapPage;
        $this->view->authors = $authors;
    }

    public function authorAction () {
        $request = $this->getRequest();
        $authorId = (int) $request->getParam('id');
        $sitemapPageId = (int) $request->getParam('sitemap_page_id');
        
        if ($sitemapPageId <= 0) {
            throw new Zend_Controller_Router_Exception('Invalid sitemap page id: ' . $sitemapPageId, 404);
        }
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
        if (!$sitemapPage) {
            throw new Zend_Controller_Router_Exception('No sitemap page is found for id: ' . $sitemapPageId, 404);
        }
        if (
            $sitemapPage['status'] == Application_Model_DbTable_CmsSitemapPages::STATUS_DISABLED &&
            !Zend_Auth::getInstance()->hasIdentity()
        ) {
            throw new Zend_Controller_Router_Exception('Sitemap page is disabled', 404);
        }
        
        
        
        $cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();
        $author = $cmsAuthorsDbTable->search(array(
           'filters' => array(
                'id' => $authorId,
            ),
           'orders' => array(
               'order_number' => 'ASC',
           )
        ));
        
        $author = $author[0];
		
		$sitemapPageCategories = $cmsSitemapPageDbTable->search(array(
			'filters' => array (
				'short_title' => 'Categories'
			)
		));
        
        $this->view->sitemapPage = $sitemapPage;
        $this->view->author = $author;
    }
}

