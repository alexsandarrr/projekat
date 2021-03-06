<?php

class ContactController extends Zend_Controller_Action
{
    public function indexAction () {
        $request = $this->getRequest();
        $sitemapPageId = (int) $request->getParam('sitemap_page_id');
        if ($sitemapPageId <= 0) {
                throw new Zend_Controller_Router_Exception('Invalid sitemap page id: ' . $sitemapPageId, 404);
        }
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
        if (!$sitemapPage) {
                throw new Zend_Controller_Router_Exception('No sitemap page is found for id: ' . $sitemapPageId, 404);
        }
        if (
                        $sitemapPage['status'] == Application_Model_DbTable_CmsSitemapPages::STATUS_DISABLED &&
                        !Zend_Auth::getInstance()->hasIdentity()
        ) {
                throw new Zend_Controller_Router_Exception('Sitemap page is disabled', 404);
        }

        $request = $this->getRequest();
        $flashMessenger = $this->getHelper('FlashMessenger');

        $form = new Application_Form_Contact();

        $systemMessages = 'init';

        if ($request->isPost() && $request->getPost('task') === 'contact') {
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {
                        throw new Application_Model_Exception_InvalidInput('Invalid form data bla bla');
                }

                //get form data
                $formData = $form->getValues();

                // do actual task
                //save to database etc
                $mailHelper = new Application_Model_Library_MailHelper();
                $from_email = $formData['email'];
                $to_email = 'alexsandarrr@gmail.com';
                $from_name = $formData['name'];
                $message = $formData['message'];

                $result = $mailHelper->sendmail($to_email, $from_email, $from_name, $message);

                if(!$result) {
                        $systemMessages = "Error";
                } else {
                        $systemMessages = "Success";
                }

            } catch (Application_Model_Exception_InvalidInput $ex) {
                    $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->sitemapPage = $sitemapPage;
        $this->view->form = $form;

    }
}


