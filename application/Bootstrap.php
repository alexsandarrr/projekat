<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initRouter() {
		//ensure that database is configured
		$this->bootstrap('db');
		
		$sitemapPageTypes = array(
			
			'StaticPage' => array(
				'title' => 'Static Page',
				'subtypes' => array(
					// 0 means unlimited number
					'StaticPage' => 0
				)
			),
			
			'PhotoGalleriesPage' => array(
				'title' => 'Photo Galleries Page',
				'subtypes' => array(
					
				)
			),
			
			'AboutPage' => array(
				'title' => 'About Page',
				'subtypes' => array(
					
				)
			),
			
			'BooksPage' => array(
				'title' => 'Books Page',
				'subtypes' => array(
					
				)
			),
			
			'BlogPage' => array(
				'title' => 'Blog Page',
				'subtypes' => array(
					
				)
			),
			
			'CategoriesPage' => array(
				'title' => 'Categories Page',
				'subtypes' => array(
					'StaticPage' => 0,
				)
			),
		);
		
		$rootSitemapPageTypes = array(
			'StaticPage' => 0,
			'PhotoGalleriesPage' => 1,
			'AboutPage' => 1,
			'BooksPage' => 1,
			'BlogPage' => 1,
			'CategoriesPage' => 0,
		);
		
		Zend_Registry::set('sitemapPageTypes', $sitemapPageTypes);
		Zend_Registry::set('rootSitemapPageTypes', $rootSitemapPageTypes);
		
		$router = Zend_Controller_Front::getInstance()->getRouter();
		
		$router instanceof Zend_Controller_Router_Rewrite;
		
		$sitmapPagesMap = Application_Model_DbTable_CmsSitemapPages::getSitemapPagesMap();
		
		foreach ($sitmapPagesMap as $sitemapPageId => $sitemapPageMap) {
			
			if ($sitemapPageMap['type'] == 'StaticPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'staticpage',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			
			if ($sitemapPageMap['type'] == 'PhotoGalleriesPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'photogalleries',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('photo-gallery-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:photo_gallery_slug',
					array(
						'controller' => 'photogalleries',
						'action' => 'gallery',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'AboutPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'about',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'BooksPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'books',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('book-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:book_slug',
					array(
						'controller' => 'books',
						'action' => 'book',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'BlogPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'blog',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('blog-post-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:blog_post_slug',
					array(
						'controller' => 'blog',
						'action' => 'blogpost',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'CategoriesPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'categories',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('category-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:category_slug',
					array(
						'controller' => 'categories',
						'action' => 'category',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
		}
	}
}

