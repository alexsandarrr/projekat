<?php

class TestController extends Zend_Controller_Action
{
	public function indexAction() {
		Zend_Layout::getMvcInstance()->disableLayout();
		
		$modelPhotos = new Application_Model_DbTable_CmsPhotos();
		$modelGallery = new Application_Model_DbTable_CmsPhotoGalleries();
		$galleries = $modelGallery->search();
		if(count($galleries) > 0){
			$images = array();
			foreach ($galleries as $gallery) {
				$images[$gallery['id']] = $modelPhotos->search(array(
					'filters'=> array(
						'photo_gallery_id' => $gallery['id'])));
			}
		}
		
		
		$this->view->galleries = $galleries;
		$this->view->images = $images;
		
	}
}