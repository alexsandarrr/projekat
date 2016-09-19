<?php

class Zend_View_Helper_FooterCategoriesHtml extends Zend_View_Helper_Abstract
{
    public function footerCategoriesHtml() {
        
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        
        $footerCategoriesSitemapPages = $cmsSitemapPageDbTable->search(array(
            'filters' => array(
				'parent_id' => 0,
                'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC'
            )
        ));
		
        // resetovanje placeholder-a
        $this->view->placeholder('footerCategoriesHtml')->exchangeArray(array());
        
        $this->view->placeholder('footerCategoriesHtml')->captureStart();
?>
		<div class="row">
					<?php 
					$i = 0;
					foreach($footerCategoriesSitemapPages as $sitemapPage) {
						if($i < 6) {?>
					<div class="col-sm-6">
						<article>
							<p><a href="<?php echo $this->view->sitemapPageUrl($sitemapPage['id']);?>"><?php echo $this->view->escape($sitemapPage['short_title']);?></a></p>
						</article>
					</div>
					<?php $i++; } else { ?>
					<div class="col-sm-6">
						<article>
							<p><a href="<?php echo $this->view->sitemapPageUrl($sitemapPage['id']);?>"><?php echo $this->view->escape($sitemapPage['short_title']);?></a></p>
						</article>
					</div>
					<?php $i++; }}?>
		</div>
<?php 
        $this->view->placeholder('footerCategoriesHtml')->captureEnd();
        
        return $this->view->placeholder('footerCategoriesHtml')->toString();
	}
}

