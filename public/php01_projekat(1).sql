-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 21, 2016 at 03:36 PM
-- Server version: 10.1.14-MariaDB
-- PHP Version: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php01_projekat`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_authors`
--

CREATE TABLE IF NOT EXISTS `cms_authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_authors`
--

INSERT INTO `cms_authors` (`id`, `name`, `about`, `order_number`, `status`) VALUES
(1, 'Herman Hese', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', 1, 1),
(2, 'J. K. Rowling', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', 2, 1),
(3, 'J.R.R. Tolkin', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', 3, 1),
(4, 'Terry Pratchett', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_blog_posts`
--

CREATE TABLE IF NOT EXISTS `cms_blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_books`
--

CREATE TABLE IF NOT EXISTS `cms_books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `format` varchar(255) NOT NULL,
  `number_of_pages` int(11) NOT NULL,
  `date_of_publication` date DEFAULT NULL,
  `about` text NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sale` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `cover_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_books`
--

INSERT INTO `cms_books` (`id`, `title`, `format`, `number_of_pages`, `date_of_publication`, `about`, `isbn`, `price`, `sale`, `category_id`, `author_id`, `cover_id`, `language_id`, `order_number`, `status`, `date_added`) VALUES
(1, 'Sidarta', '130X130', 150, '2016-09-01', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', '987-65-432-1000-0', 650.00, 1, 9, 1, 1, 1, 1, 1, '2016-09-16 11:33:27'),
(2, 'Stepski vuk', '150X150', 250, '2016-05-23', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', '999-9999-999-9999', 230.00, 0, 9, 1, 1, 1, 2, 1, '2016-09-17 11:33:27'),
(3, 'Harry Potter i Zatvorenik iz Azkabana', '160X160', 350, '2015-05-14', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', '888-888-888-888', 1120.00, 0, 8, 2, 2, 1, 3, 1, '2016-09-19 11:33:27'),
(4, 'Harry Potter i Red Feniksa', '160X160', 836, '2016-05-17', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', '77-7777-777-7777', 1540.00, 0, 8, 2, 2, 2, 4, 1, '2016-09-19 11:33:27'),
(5, 'Harry Potter i Relikvije smrti', '160X160', 786, '2016-09-04', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', '333-33-33333-333', 1350.00, 1, 8, 2, 1, 1, 5, 1, '2016-09-19 11:33:27'),
(6, 'Hobit', '155X155', 501, '2016-08-08', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', '89-968-7895-548', 983.00, 0, 8, 3, 2, 2, 6, 1, '2016-09-19 11:33:27'),
(10, 'Nocna straza', '150X150', 234, '2003-12-05', 'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque', '00000-222-29999-888', 123.00, 0, 11, 4, 2, 1, 7, 1, '2016-09-18 11:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `cms_covers`
--

CREATE TABLE IF NOT EXISTS `cms_covers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_covers`
--

INSERT INTO `cms_covers` (`id`, `title`, `order_number`) VALUES
(1, 'Hardcover', 0),
(2, 'Papercover', 0),
(3, 'ssss', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_index_slides`
--

CREATE TABLE IF NOT EXISTS `cms_index_slides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `link_type` char(50) NOT NULL DEFAULT 'NoLink',
  `link_label` varchar(255) DEFAULT NULL,
  `sitemap_page_id` int(11) DEFAULT NULL,
  `internal_link_url` varchar(255) DEFAULT NULL,
  `external_link_url` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_index_slides`
--

INSERT INTO `cms_index_slides` (`id`, `title`, `description`, `link_type`, `link_label`, `sitemap_page_id`, `internal_link_url`, `external_link_url`, `status`, `order_number`) VALUES
(4, 'Slider 1', 'Slider 1', 'NoLink', '', 0, '', '', 1, 1),
(5, 'Slide 2', 'Slide 2', 'NoLink', '', 0, '', '', 1, 2),
(6, 'Slide 3', 'Slide 3', 'NoLink', '', 0, '', '', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cms_languages`
--

CREATE TABLE IF NOT EXISTS `cms_languages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_languages`
--

INSERT INTO `cms_languages` (`id`, `title`, `order_number`) VALUES
(1, 'Serbian Latin', 0),
(2, 'Serbian Cyrillic', 0),
(3, 'English', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_photos`
--

CREATE TABLE IF NOT EXISTS `cms_photos` (
  `id` int(11) NOT NULL,
  `photo_gallery_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_photos`
--

INSERT INTO `cms_photos` (`id`, `photo_gallery_id`, `title`, `description`, `status`, `order_number`) VALUES
(1, 4, 'hfkfkhfgk', 'hgkfkfhkhf', 1, 1),
(2, 4, 'vbcxbcvbcx', 'bcxvbxcvbxcv', 1, 2),
(3, 4, 'vbxcvbcvbcvbxc', 'cxvbxcvbcxvxbxcvb', 1, 3),
(4, 4, '', '', 1, 4),
(5, 3, '', '', 1, 1),
(6, 3, '', '', 1, 2),
(7, 3, '', '', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cms_photo_galleries`
--

CREATE TABLE IF NOT EXISTS `cms_photo_galleries` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_photo_galleries`
--

INSERT INTO `cms_photo_galleries` (`id`, `title`, `description`, `status`, `order_number`) VALUES
(1, 'Shop Photos', 'Pictures of our shops.', 1, 1),
(2, 'Events', 'Events in our Book Shops', 1, 2),
(3, 'Coffee Shop', 'Our Coffee Shop.', 1, 3),
(4, 'ccccccc', '', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cms_services`
--

CREATE TABLE IF NOT EXISTS `cms_services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_services`
--

INSERT INTO `cms_services` (`id`, `title`, `icon`, `description`, `status`, `order_number`) VALUES
(1, 'Purchase of used books', 'fa fa-book', 'Purchase of used books. Purchase of used books. Purchase of used books. Purchase of used books.', 1, 1),
(2, 'Sale of used books', 'fa fa-money', 'Sale of used books. Sale of used books. Sale of used books. Sale of used books. Sale of used books.', 1, 2),
(3, 'vbdfhfdhshfsd', 'fa fa-key', 'dafsfdasdf dfasdf asdf sadf asdf sadf asdf', 1, 3),
(4, 'dafsdf dfasdfasdf', 'fa fa-book', 'f asdfasdf adfdf asdfdsaf asdf asdfasdfsa', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cms_sitemap_pages`
--

CREATE TABLE IF NOT EXISTS `cms_sitemap_pages` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `type` char(255) NOT NULL,
  `url_slug` char(255) NOT NULL,
  `short_title` varchar(255) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `body` longtext,
  `status` int(11) NOT NULL DEFAULT '1',
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_sitemap_pages`
--

INSERT INTO `cms_sitemap_pages` (`id`, `parent_id`, `type`, `url_slug`, `short_title`, `title`, `description`, `body`, `status`, `order_number`) VALUES
(1, 0, 'AboutPage', 'About-Page', 'About', 'About Page', 'About Page', '<!--******************************************\r\n                        MAIN START\r\n        *******************************************-->\r\n<div class="container">\r\n<div class="main-img"><img alt="" class="img-responsive center-block" src="/front/img/aboutUs.png" /></div>\r\n\r\n<div class="text-center">\r\n<h3 class="titleAbout">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore</h3>\r\n</div>\r\n\r\n<div class="modernDesign">\r\n<h4 class="text-center">Welcome to the best in modern design</h4>\r\n\r\n<div class="row">\r\n<div class="col-sm-6">\r\n<article>\r\n<p>Ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore</p>\r\n\r\n<p>Beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>\r\n\r\n<p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n</article>\r\n</div>\r\n\r\n<div class="col-sm-6">\r\n<article>\r\n<p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?</p>\r\n<img alt="BookStore" class="img-responsive center-block" src="/front/img/aboutUs-smal.png" /></article>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="modernDesign confidance">\r\n<h4 class="text-center">Shop with Confidance</h4>\r\n\r\n<div class="row">\r\n<div class="col-sm-6">\r\n<article>\r\n<p>Ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore</p>\r\n\r\n<p>Beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolo</p>\r\n</article>\r\n</div>\r\n\r\n<div class="col-sm-6">\r\n<article>\r\n<p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?</p>\r\n</article>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<!--******************************************\r\n        **********************************************\r\n                            MAIN END\r\n        *********************************************\r\n        *********************************************--></main>\r\n', 1, 1),
(2, 0, 'BooksPage', 'Books-Page', 'Books', 'Books Page', 'Books Page', '<p>Books Page</p>\r\n', 1, 3),
(3, 0, 'BlogPage', 'Blog-Page', 'Blog', 'Blog Page', 'Blog Page', '<p>Blog Page</p>\r\n', 0, 5),
(4, 0, 'StaticPage', 'Static-Page', 'Static', 'Static Page', 'Static Page', '<p>Static Page</p>\r\n', 0, 6),
(5, 0, 'PhotoGalleriesPage', 'Photo-Galleries-Page', 'Photo Galleries', 'Photo Galleries Page', 'Photo Galleries Page', '<p>Photo Galleries Page</p>\r\n', 1, 7),
(6, 0, 'BookCategoriesPage', 'Book-Categories-Page', 'Book Categories', 'Book Categories Page', 'Book Categories Page', '<p>Book Categories Page</p>\r\n', 1, 2),
(7, 6, 'BookCategoryPage', 'Trillers', 'Trillers', 'Trillers', 'Trillers', '<p>Trillers</p>\r\n', 1, 1),
(8, 6, 'BookCategoryPage', 'Epic-Fantasy', 'Epic Fantasy', 'Epic Fantasy', 'Epic Fantasy', '<p>Epic Fantasy</p>\r\n', 1, 2),
(9, 6, 'BookCategoryPage', 'Classics', 'Classics', 'Classics', 'Classics', '<p>Classics</p>\r\n', 1, 3),
(10, 0, 'AuthorsPage', 'Authors-Page', 'Authors', 'Authors Page', 'Authors Page', '<p>Authors Page</p>\r\n', 1, 4),
(11, 6, 'BookCategoryPage', 'Fiction', 'Fiction', 'Fiction', 'Fiction', '<p>Fiction</p>\r\n', 1, 4),
(12, 0, 'ContactPage', 'Contact-Page', 'Contact', 'Contact Page', 'Contact Page', '<section class="contactForm">\r\n<div class="container">\r\n<h2 class="text-center">Contact Us</h2>\r\n\r\n<form data-toggle="validator" id="form" role="form">\r\n<div class="form-group"><label for="exampleInputEmail1">Ime</label> <input class="form-control" data-error="Unesite Vase ime" pattern="^[_A-z]{1,}$" placeholder="Please enter your name" required="" type="text" />\r\n<div class="help-block with-errors">&nbsp;</div>\r\n</div>\r\n\r\n<div class="form-group"><label for="exampleInputPassword1">Email</label> <input class="form-control" data-error="Unesite ispravnu email adresu" placeholder="Please enter valid mail" required="" type="email" />\r\n<div class="help-block with-errors">&nbsp;</div>\r\n</div>\r\n\r\n<div class="form-group"><label for="exampleInputPassword1">Subject</label> <input class="form-control" placeholder="" type="text" /></div>\r\n\r\n<div class="form-group"><textarea class="form-control" placeholder="Please write some text"></textarea></div>\r\n<button class="btn btn-warning center-block" type="submit">Posalji</button></form>\r\n</div>\r\n</section>\r\n\r\n<section class="maps">\r\n<div class="container"><iframe allowfullscreen="" frameborder="0" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d18547.41065443684!2d20.415602186489288!3d44.82780028264034!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ssr!2srs!4v1470131468369" style="border:0"></iframe></div>\r\n</section>\r\n', 1, 8),
(13, 0, 'ServicesPage', 'Services-Page', 'Services', 'Services Page', 'Services Page', '<p>Services Page</p>\r\n', 1, 9),
(14, 0, 'LatestBooksPage', 'Latest-Books-Page', 'Latest Books', 'Latest Books Page', 'Latest Books Page', '<p>Latest Books Page</p>\r\n', 1, 10),
(15, 0, 'BooksOnSalePage', 'Books-On-Sale-Page', 'Books On Sale', 'Books On Sale Page', 'Books On Sale Page', '<p>Books On Sale Page</p>\r\n', 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `cms_users`
--

CREATE TABLE IF NOT EXISTS `cms_users` (
  `id` int(11) NOT NULL,
  `username` char(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_users`
--

INSERT INTO `cms_users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `status`) VALUES
(1, 'cubes', '7182821555d2101b3c64a14b82e12e2c', '', '', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_authors`
--
ALTER TABLE `cms_authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_blog_posts`
--
ALTER TABLE `cms_blog_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_books`
--
ALTER TABLE `cms_books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `category_id_2` (`category_id`),
  ADD KEY `cover_id` (`cover_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id_3` (`category_id`);

--
-- Indexes for table `cms_covers`
--
ALTER TABLE `cms_covers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_index_slides`
--
ALTER TABLE `cms_index_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_languages`
--
ALTER TABLE `cms_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_photos`
--
ALTER TABLE `cms_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_photo_galleries`
--
ALTER TABLE `cms_photo_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_services`
--
ALTER TABLE `cms_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sitemap_pages`
--
ALTER TABLE `cms_sitemap_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_sitemap_page_parent_slug_unique` (`parent_id`,`url_slug`);

--
-- Indexes for table `cms_users`
--
ALTER TABLE `cms_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ix_cms_users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_authors`
--
ALTER TABLE `cms_authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cms_blog_posts`
--
ALTER TABLE `cms_blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cms_books`
--
ALTER TABLE `cms_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `cms_covers`
--
ALTER TABLE `cms_covers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cms_index_slides`
--
ALTER TABLE `cms_index_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `cms_languages`
--
ALTER TABLE `cms_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cms_photos`
--
ALTER TABLE `cms_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `cms_photo_galleries`
--
ALTER TABLE `cms_photo_galleries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cms_services`
--
ALTER TABLE `cms_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cms_sitemap_pages`
--
ALTER TABLE `cms_sitemap_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `cms_users`
--
ALTER TABLE `cms_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
