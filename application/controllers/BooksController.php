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
			'limit' => 50
		));
		
                // ----- FILTERS -----
                
		$cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();
		$authors = $cmsAuthorsDbTable->search(array(
                    'orders' => array(
                        'order_number' => 'ASC'
                    ),
		));
                
                $sitemapPageCategories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'short_title' => 'Book Categories',
				'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
			)
		));

		$categoryId = $sitemapPageCategories[0]['id'];
		$categories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'parent_id' => $categoryId,
				'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
		));
                
                $cmsCoversDbTable = new Application_Model_DbTable_CmsCovers();
		$covers = $cmsCoversDbTable->search(array(
                    'orders' => array(
                        'order_number' => 'ASC'
                    ),
		));
                
                $cmsLanguagesDbTable = new Application_Model_DbTable_CmsLanguages();
		$languages = $cmsLanguagesDbTable->search(array(
                    'orders' => array(
                        'order_number' => 'ASC'
                    ),
		));
                
        $form = new Application_Form_FilterBooks();

        $form->populate(array());
        
        //ukoliko je izabran filter
        if ($request->isPost() && $request->getPost('task') === 'filter') {//ispitujemo da lije pokrenuta forma
                // die('forma');
                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for products');
                }
                $formData = $form->getValues();
//             print_r($formData);
//                die();
                //get all products
                //ukoliko nije filtrirano po nekoj od kategorija
                $cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();

                if (empty($formData['category_id'])) {
                    foreach ($categories as $category) {
                        $formData['category_id'][] = $category['id'];
                    }
                }
                if (empty($formData['author_id'])) {
                    foreach ($authors as $author) {
                        $formData['author_id'][] = $author['id'];
                    }
                }
                if (empty($formData['cover_id'])) {
                    foreach ($covers as $cover) {
                        $formData['cover_id'][] = $cover['id'];
                    }
                }
                if (empty($formData['language_id'])) {
                    foreach ($languages as $language) {
                        $formData['language_id'][] = $language['id'];
                    }
                }


                $books = $cmsBooksDbTable->search(array(
                    'filters' => array(
                        'status' => Application_Model_DbTable_CmsBooks::STATUS_ENABLED,
                        'category_id' => $formData['category_id'],
                        'author_id' => $formData['author_id'],
                        'cover_id' => $formData['cover_id'],
                        'language_id' => $formData['language_id'],
                    ),
                    'orders' => array(
                        'order_number' => 'ASC',
                    ),
                        //'limit' => 4,
                        //'page' => 2
                ));
                $this->view->books = $books;
        } else {

            //get all products ukoliko nema filtera
            $cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();
            $books = $cmsBooksDbTable->search(array(
                'filters' => array(
                    'status' => Application_Model_DbTable_CmsBooks::STATUS_ENABLED
                ),
                'orders' => array(
                    'order_number' => 'ASC',
                ),
                    //'limit' => 4,
                    //'page' => 2
            ));
            $this->view->books = $books;
        }
//                print_r($categories);
//                die();
		$this->view->sitemapPage = $sitemapPage;
		$this->view->authors = $authors;
                $this->view->categories = $categories;
                $this->view->covers = $covers;
                $this->view->languages = $languages;
                $this->view->form =  $form;
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
		$book = $cmsBooksDbTable->search(array(
			'filters' => array(
				'id' => $bookId,
				'status' => Application_Model_DbTable_CmsBooks::STATUS_ENABLED
			),
			'orders' => array(
				'order_number' => 'ASC',
			)
		));

		$book = $book[0];

		$sitemapPageCategories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'short_title' => 'Book Categories',
				'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
			)
		));

		$categoryId = $sitemapPageCategories[0]['id'];
		$categories = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'parent_id' => $categoryId,
				'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
		));
                
                $books = array();
		if (count($categories) > 0) {
                    foreach ($categories as $category) {
                        $books[$category['id']] = $cmsBooksDbTable->search(array(
                            'filters' => array(
                                'category_id' => $category['id'],
                                'status' => Application_Model_DbTable_CmsBooks::STATUS_ENABLED
                            ),
                            'orders' => array(
                                    'order_number' => 'ASC',
                            )
                            ));
			}
		}
                
		$cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();
		$author = $cmsAuthorsDbTable->search(array(
		   'filters' => array(
			   'id' => $book['author_id'],
		   ), 
		));
		$author = $author[0];
		
		$relatedBooks = $cmsBooksDbTable->search(array(
			'filters' => array(
				'author_id' => $author['id'],
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
		));
                
                foreach($categories as $category) {
                    if ($book['category_id'] == $category['id']) {
                        $bookCategory = $category['short_title'];
                    }
                } 
		
		$this->view->sitemapPage = $sitemapPage;
		$this->view->book = $book;
		$this->view->categories = $categories;
                $this->view->author = $author;
		$this->view->relatedBooks = $relatedBooks;
                $this->view->bookCategory = $bookCategory;
                $this->view->books = $books;
	}

}
