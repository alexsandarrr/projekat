<?php

class CategoriesController extends Zend_Controller_Action {
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

		$sitemapPageCategories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'id' => $sitemapPageId
			)
		));
                
		$categoryId = $sitemapPageCategories[0]['id'];
		$categories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'parent_id' => $categoryId
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
		));
//print_r($categories);
//die();
		$cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();
		$books = array();
		if (count($categories) > 0) {
			foreach ($categories as $category) {
				$books[$category['id']] = $cmsBooksDbTable->search(array(
					'filters' => array(
						'category_id' => $category['id']
					),
					'orders' => array(
						'order_number' => 'ASC',
					),
					'limit' => 5
						)
				);
			}
		}

		$this->view->sitemapPage = $sitemapPage;
		$this->view->books = $books;
		$this->view->categories = $categories;
    }
	
	public function categoryAction () {
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

		$sitemapPageCategories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'id' => $sitemapPageId,
			)
		));
		
		$id = $request->getParam('id');
		$categoryId = $sitemapPageCategories[0]['id'];
		$category = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'id' => $id,
				'parent_id' => $categoryId,
				
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
		));
		
		$category = $category[0];
		
		$cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();
		$books = array();
				$books[$category['id']] = $cmsBooksDbTable->search(array(
					'filters' => array(
						'category_id' => $category['id']
					),
					'orders' => array(
						'order_number' => 'ASC',
					),
				));
				
		$books = $books[$category['id']];
				
//		print_r($category);
//		die();
		
		$this->view->sitemapPage = $sitemapPage;
		$this->view->books = $books;
		$this->view->category = $category;
	}
}