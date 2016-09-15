<?php

class Zend_View_Helper_TopMenuHtml extends Zend_View_Helper_Abstract
{
    public function topMenuHtml() {
        
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        
        $topMenuSitemapPages = $cmsSitemapPageDbTable->search(array(
            'filters' => array(
				'parent_id' => 0,
                'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC'
            )
        ));
		
        // resetovanje placeholder-a
        $this->view->placeholder('topMenuHtml')->exchangeArray(array());
        
        $this->view->placeholder('topMenuHtml')->captureStart();
?>
        
        <ul class="nav navbar-nav">
                <li>
                        <a href="<?php echo $this->view->baseUrl('/');?>">Home</a>
                </li>
                <?php foreach ($topMenuSitemapPages as $sitemapPage) {
					$childElements = $cmsSitemapPageDbTable->search(array(
						'filters' => array(
							'parent_id' => $sitemapPage['id'],
							'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
						),
						'orders' => array(
							'order_number' => 'ASC'
						)
					));
					if (!empty($childElements)) { ?>
						<li class="dropdown">
							<a class="dropdown-toggle" aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" href="<?php echo $this->view->sitemapPageUrl($sitemapPage['id']);?>">
								<?php echo $this->view->escape($sitemapPage['short_title']);?>
								<span class="caret"></span>
							</a>
						<ul class="dropdown-menu">
							<?php foreach($childElements as $childElement) {?>
							
							<li>
							<a href="<?php echo $this->view->sitemapPageUrl($childElement['id']);?>"><?php echo $this->view->escape($childElement['short_title']);?></a>
							</li>
							<?php }?>
						</ul>
                </li>
				<?php } else {?>
                <li>
					<a href="<?php echo $this->view->sitemapPageUrl($sitemapPage['id']);?>">
						<?php echo $this->view->escape($sitemapPage['short_title']);?>
					</a>
				</li>
                
				<?php }} ?>
        </ul>
        
<?php 
        $this->view->placeholder('topMenuHtml')->captureEnd();
        
        return $this->view->placeholder('topMenuHtml')->toString();
    }
}

