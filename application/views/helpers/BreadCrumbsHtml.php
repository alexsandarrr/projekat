<?php

class Zend_View_Helper_BreadCrumbsHtml extends Zend_View_Helper_Abstract
{
    public function breadCrumbsHtml($id) {
		
		$cmsSitemapPagesDbTable = new Application_Model_DbTable_CmsSitemapPages();
		
		$childSitemapPages = $cmsSitemapPagesDbTable->search(array(
			'filters' => array(
				'parent_id' => $id
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
		));
		
		$sitemapPageBreadcrumbs = $cmsSitemapPagesDbTable->getSitemapPageBreadcrumbs($id);
		
		
		$this->view->placeholder('breadCrumbsHtml')->exchangeArray(array());
        
        $this->view->placeholder('breadCrumbsHtml')->captureStart();
		
?>
		<ol class="breadcrumb text-center">
			<li>
				<a href="/"></i> Home</a>
			</li>
			<?php foreach ($sitemapPageBreadcrumbs as $sitemapPage) {?>
			<li>
				<a href="<?php echo $this->view->sitemapPageUrl($sitemapPage['id']);?>"><?php echo $sitemapPage['short_title'];?></a>
			</li>
			<?php }?>
        </ol>
<?php
		$this->view->placeholder('breadCrumbsHtml')->captureEnd();
        
        return $this->view->placeholder('breadCrumbsHtml')->toString();

	}
}

