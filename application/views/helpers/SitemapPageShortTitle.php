<?php

class Zend_View_Helper_SitemapPageShortTitle extends Zend_View_Helper_Abstract {

	public function sitemapPageShortTitle($id) {

		$sitemapPageModel = new Application_Model_DbTable_CmsSitemapPages();
		$sitemapPage = $sitemapPageModel->find($id)->current();

		if (isset($sitemapPage)) {
			return $sitemapPage['short_title'];
		} else {

			return '';
		}
	}

}
