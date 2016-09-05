<?php

class Application_Model_DbTable_CmsBooks extends Zend_Db_Table_Abstract
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    protected $_name = 'cms_books';
    
    /** 
     * @param int $id
     * @return null|array Associative array with keys as cms_books table columns or NULL if not found
     */
    public function getBookById ($id) {
        
        $select = $this->select();
        $select->where('id = ?', $id);
        
        $row = $this->fetchRow($select);
        
        if ($row instanceof Zend_Db_Table_Row) {
            
            return $row->toArray();
        } else {
            // row is not found
            return null;
        }
    }
    
    /**
     * @param int $id
     * @param array $book Associative array with keys as column names and values as column new values
     */
    public function updateBook ($id, $book) {
        
        if (isset($book['id'])) {
            // Forbid changing of user id
            unset($book['id']);
        }
        
        $this->update($book, 'id = ' . $id);
    }
    
    /**
     * @param array $book Associative array with keys as column names and values as column new values
     * @return int The ID of new book (autoincrement)
     */
    public function insertBook ($book) {
        // fetch order number for new book
        
        $select = $this->select();
        
        // sort rows by order_number DESCENDING and fetch one row from the top
        // with biggest order_number
        $select->order('order_number DESC');
        
        $bookWithBiggerstOrderNumber = $this->fetchRow($select);
        
        if ($bookWithBiggerstOrderNumber instanceof Zend_Db_table_Row) {
            
            $book['order_number'] = $bookWithBiggerstOrderNumber['order_number'] + 1;
            
        } else {
            // table was empty, we are inserting first book
            $book['order_number'] = 1;
        }
        
        $id = $this->insert($book);
        
        return $id;
    }
    
    /**
     * @param int $id ID of book to delete
     */
    public function deleteBook ($id) {
        
        $bookPhotoFilePath = PUBLIC_PATH . '/uploads/books/' . $id . '.jpg';
        if(is_file($bookPhotoFilePath)) {
            //delete book photo file
            unlink($bookPhotoFilePath);
        }
        
        // book who is going to be deleted
        $book = $this->getBookById($id);
        
        $this->update(array(
           'order_number' => new Zend_Db_Expr('order_number - 1') 
        ),
        'order_number > ' . $book['order_number']);
        
        $this->delete('id = ' . $id);
    }
    
    /**
     * @param int $id ID of book to disable
     */
    public function disableBook ($id) {
        
        $this->update(array(
            'status' => self::STATUS_DISABLED
        ), 'id = ' . $id);
    }
    
    /**
     * @param int $id ID of book to enable
     */
    public function enableBook ($id) {
        
        $this->update(array(
            'status' => self::STATUS_ENABLED
        ), 'id = ' . $id);
    }
    
    public function updateOrderOfBooks ($sortedIds) {
        foreach ($sortedIds as $orderNumber => $id) {
            
            $this->update(array(
            'order_number' => $orderNumber + 1 // +1 because order_number starts from 1, not from 0
        ), 'id = ' . $id);
            
        }
    }
    
    /**
     * Array $parameters is keeping search parameters.
     * Array $parameters must be in following format:
     *      array(
     *          'filters' => array(
     *              'status' => 1,
     *              'id' => array(3, 8, 11),
     *          ),
     *          'orders' =>array(
     *              'username' => 'ASC', //key is column, if value is ASC then ORDER BY ASC
     *              'first_name' => 'DESC', // key is column, if value is DESC then ORDER BY DESCS
     *          ),
     *          'limit' => 50, // limit result set to 50 rows
     *          'page' => 3 // start from page 3. If no limit is set, page is ignored
     *      )
     * @param array $parameters Asoc array with keys "filters", "orders", "limit" and "page".
     */
    public function search (array $parameters = array()) {
        
        $select = $this->select();
        
        if (isset($parameters['filters'])) {
            
            $filters = $parameters['filters'];
            
            $this->processFilters($filters, $select);
            
        }
        
        if (isset($parameters['orders'])) {
            
            $orders = $parameters['orders'];
            
            foreach ($orders as $field => $orderDirection) {
                
                switch ($field) {
                    
                    case 'id':
                    case 'first_name':
                    case 'last_name':
                    case 'email':
                    case 'status':
                    case 'order_number':
                    case 'work_title':
                        if ($orderDirection === 'DESC') {
                            $select->order($field . ' DESC');
                        } else {
                            $select->order($field);
                        }
                        break;
                }
            }
        }
        
        if (isset($parameters['limit'])) {
            
            if (isset($parameters['page'])) {
                // page is set do limit by page
                $select->limitPage($parameters['page'], $parameters['limit']);
            } else {
                // page is not set, just do regular limit
                $select->limit($parameters['limit']);
            }
        }
        
        //die ($select->assemble());
        
        return $this->fetchAll($select)->toArray();
    }
    
    /**
     * @param array $filters See function search $parameters['filters']
     * @return int Count of rows that match $filters
     */
    public function count(array $filters = array()) {
        
        $select = $this->select();
        
        $this->processFilters($filters, $select);
        
        // reset previously set columns for resultset
        $select->reset('columns');
        // set one column/field to fetch and it is COUNT function
        $select->from($this->_name, 'COUNT(*) as total');
        
        $row = $this->fetchRow($select);
        
        return $row['total'];
    }
    
    /**
     * Fill $select object with WHERE conditions
     * @param array $filters
     * @param Zend_Db_Select $select
     */
    protected function processFilters(array $filters, Zend_Db_Select $select) {
        
        // $select ovject will be modified outside this function
        // obect are always passed by reference
        foreach ($filters as $field => $value) {
                
            if ($field == 'id') {

                if (is_array($value)) {
                    $select->where('id IN (?)', $value);
                } else {
                    $select->where('id = ?', $value);
                }
            }

            switch ($field) {

                case 'id':
                case 'first_name':
                case 'last_name':
                case 'email':
                case 'status':
                case 'work_title':
                    if (is_array($value)) {
                        $select->where($field . ' IN (?)', $value);
                    } else {
                        $select->where($field . ' = ?', $value);
                    }
                    break;

                case 'first_name_search':
                    $select->where('first_name LIKE ?', '%' . $value . '%');
                    break;

                case 'last_name_search':
                    $select->where('last_name LIKE ?', '%' . $value . '%');
                    break;

                case 'email_search':
                    $select->where('email LIKE ?', '%' . $value . '%');
                    break;

                case 'id_exclude':
                    if (is_array($value)) {
                        $select->where('id NOT IN (?)', $value);
                    } else {
                        $select->where('id != ?', $value);
                    }
                    break;
            }
        }
    }
}

