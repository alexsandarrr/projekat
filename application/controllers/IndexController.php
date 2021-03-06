<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $cmsIndexSlidesDbTable = new Application_Model_DbTable_CmsIndexSlides();

        $indexSlides = $cmsIndexSlidesDbTable->search(array(
                'filters' => array(
                        'status' => Application_Model_DbTable_CmsIndexSlides::STATUS_ENABLED
                ),
                'orders' => array(
                        'order_number' => 'ASC'
                )
        ));
		
		$cmsBooksDbTable = new Application_Model_DbTable_CmsBooks();
		$books = $cmsBooksDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsBooks::STATUS_ENABLED,
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
			'limit' => 5,
		));
		
		$shelfBooks = $cmsBooksDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsBooks::STATUS_ENABLED,
			),
			'orders' => array(
				'date_added' => 'DESC'
			),
			'limit' => 4,
		));
		
		$cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
        $services = $cmsServicesDbTable->search (array(
            'filters' => array (
                'status' => Application_Model_DbTable_CmsServices::STATUS_ENABLED,
            ),
            'orders' => array (
                'order_number' => 'ASC'
            ),
            'limit' => 4
        ));

        $this->view->indexSlides = $indexSlides;
		$this->view->books = $books;
		$this->view->shelfBooks = $shelfBooks;
		$this->view->services = $services;
    }
}

