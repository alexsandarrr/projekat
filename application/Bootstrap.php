<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initRouter() {
		$this->bootstrap('db');
		
		$sitemapPageTypes = array(
			
			'StaticPage' => array(
				'title' => 'Static Page',
				'subtypes' => array(
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
			
			'BookCategoriesPage' => array(
				'title' => 'Book Categories Page',
				'subtypes' => array(
					'StaticPage' => 0,
					'BookCategoryPage' => 0,
				)
			),
			'BookCategoryPage' => array(
				'title' => 'Book Category Page',
				'subtypes' => array(
					
				)
			),
			
			'AuthorsPage' => array(
				'title' => 'Authors Page',
				'subtypes' => array(
					
				)
			),
			
			'ContactPage' => array(
				'title' => 'Contact Page',
				'subtypes' => array(
					
				)
			),
			
			'ServicesPage' => array(
				'title' => 'Services Page',
				'subtypes' => array(
					
				)
			),
			
			'LatestBooksPage' => array(
				'title' => 'Latest Books Page',
				'subtypes' => array(
					
				)
			),
			
			'BooksOnSalePage' => array(
				'title' => 'Books On Sale Page',
				'subtypes' => array(
					
				)
			),
		);
		
		$rootSitemapPageTypes = array(
			'StaticPage' => 0,
			'PhotoGalleriesPage' => 1,
			'AboutPage' => 1,
			'BooksPage' => 1,
			'BlogPage' => 1,
			'BookCategoriesPage' => 1,
			'AuthorsPage' => 1,
			'ContactPage' => 1,
			'ServicesPage' => 1,
			'LatestBooksPage' => 1,
			'BooksOnSalePage' => 1,
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
			
			if ($sitemapPageMap['type'] == 'BookCategoriesPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'bookcategories',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'BookCategoryPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'bookcategories',
						'action' => 'bookcategory',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'AuthorsPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'authors',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('author-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:author_slug',
					array(
						'controller' => 'authors',
						'action' => 'author',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'ContactPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'contact',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'ServicesPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'services',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('service-route-' . $sitemapPageId, new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:service_slug',
					array(
						'controller' => 'services',
						'action' => 'service',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'LatestBooksPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'latestbooks',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			if ($sitemapPageMap['type'] == 'BooksOnSalePage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'booksonsale',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
		}
	}
}

