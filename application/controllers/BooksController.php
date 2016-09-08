<?php

class BooksController extends Zend_Controller_Action {

	public function indexAction() {
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

	

		$cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();
		$books = $cmsBooksDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsBooks::STATUS_ENABLED,
				),
			'orders' => array(
				'order_number' => 'ASC'
			),
			'limit' => 20
		));
		
		

		$this->view->sitemapPage = $sitemapPage;
		$this->view->books = $books;
	}

	public function bookAction() {
		$request = $this->getRequest();
		$bookId = (int) $request->getParam('id');
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

		$cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();
		$books = $cmsBooksDbTable->search(array(
			'orders' => array(
				'order_number' => 'ASC',
			)
		));

		$cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();
		$book = $cmsBooksDbTable->search(array(
			'filters' => array(
				'id' => $bookId,
			),
			'orders' => array(
				'order_number' => 'ASC',
			)
		));

		$book = $book[0];

		$sitemapPageCategories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'short_title' => 'Categories'
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
                
                $cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();
                $author = $cmsAuthorsDbTable->search(array(
                   'filters' => array(
                       'id' => $book['author_id']
                   ), 
                ));
                $author = $author[0];

		$this->view->sitemapPage = $sitemapPage;
		$this->view->book = $book;
		$this->view->categories = $categories;
        $this->view->author = $author;
	}

}
