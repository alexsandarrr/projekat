<?php

class Application_Form_FilterBooks extends Zend_Form
{
    public function init () {
        $cmsSitemapPagesDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPageCategories = $cmsSitemapPagesDbTable->search(array(
            'filters' => array (
                'short_title' => 'Book Categories'
            )
        ));
        $categoriesId = $sitemapPageCategories[0]['id'];
        $categories = $cmsSitemapPagesDbTable->search(array(
            'filters' => array (
                'parent_id' => $categoriesId
            )
        ));

        $cmsAuthorsDbTable = new Application_Model_DbTable_CmsAuthors();
        $authors = $cmsAuthorsDbTable->search();

        $cmsCoversDbTable = new Application_Model_DbTable_CmsCovers();
        $covers = $cmsCoversDbTable->search();

        $cmsLanguagesDbTable = new Application_Model_DbTable_CmsLanguages();
        $languages = $cmsLanguagesDbTable->search();
        
        $categoryId = new Zend_Form_Element_MultiCheckbox('category_id');
        foreach ($categories as $category) {
                $categoryId->addMultiOption($category['id'], $category['short_title']);
        }
        $this->addElement($categoryId);

        $authorId = new Zend_Form_Element_MultiCheckbox('author_id');
        foreach ($authors as $author) {
                $authorId->addMultiOption($author['id'], $author['name']);
        }
        $this->addElement($authorId);

        $coverId = new Zend_Form_Element_MultiCheckbox('cover_id');
        foreach ($covers as $cover) {
                $coverId->addMultiOption($cover['id'], $cover['title']);
        }
        $this->addElement($coverId);

        $languageId = new Zend_Form_Element_MultiCheckbox('language_id');
        foreach ($languages as $language) {
                $languageId->addMultiOption($language['id'], $language['title']);
        }
        $this->addElement($languageId);
    }
}

