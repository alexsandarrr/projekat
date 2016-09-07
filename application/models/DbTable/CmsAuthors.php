<?php

class Application_Model_DbTable_CmsAuthors extends Zend_Db_Table_Abstract
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    protected $_name = 'cms_authors';
    
    /** 
     * @param int $id
     * @return null|array Associative array with keys as cms_authors table columns or NULL if not found
     */
    public function getAuthorById ($id) {
        
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
     * @param array $author Associative array with keys as column names and values as column new values
     */
    public function updateAuthor ($id, $author) {
        
        if (isset($author['id'])) {
            // Forbid changing of user id
            unset($author['id']);
        }
        
        $this->update($author, 'id = ' . $id);
    }
    
    /**
     * @param array $author Associative array with keys as column names and values as column new values
     * @return int The ID of new author (autoincrement)
     */
    public function insertAuthor ($author) {
        // fetch order number for new author
        
        $select = $this->select();
        
        $select->order('order_number DESC');
        
        $authorWithBiggerstOrderNumber = $this->fetchRow($select);
        
        if ($authorWithBiggerstOrderNumber instanceof Zend_Db_table_Row) {
            
            $author['order_number'] = $authorWithBiggerstOrderNumber['order_number'] + 1;
            
        } else {
			
            $author['order_number'] = 1;
        }
        
        $id = $this->insert($author);
        
        return $id;
    }
    
    /**
     * @param int $id ID of author to delete
     */
    public function deleteAuthor ($id) {
        
        $authorPhotoFilePath = PUBLIC_PATH . '/uploads/authors/' . $id . '.jpg';
        if(is_file($authorPhotoFilePath)) {
			
            unlink($authorPhotoFilePath);
        }
        
        $author = $this->getAuthorById($id);
        
        $this->update(array(
           'order_number' => new Zend_Db_Expr('order_number - 1') 
        ),
        'order_number > ' . $author['order_number']);
        
        $this->delete('id = ' . $id);
    }
    
    /**
     * @param int $id ID of author to disable
     */
    public function disableAuthor ($id) {
        
        $this->update(array(
            'status' => self::STATUS_DISABLED
        ), 'id = ' . $id);
    }
    
    /**
     * @param int $id ID of author to enable
     */
    public function enableAuthor ($id) {
        
        $this->update(array(
            'status' => self::STATUS_ENABLED
        ), 'id = ' . $id);
    }
    
    public function updateOrderOfAuthors ($sortedIds) {
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
                    case 'name':
                    case 'order_number':
                    case 'status':
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
				
                $select->limitPage($parameters['page'], $parameters['limit']);
            } else {
				
                $select->limit($parameters['limit']);
            }
        }
        
        return $this->fetchAll($select)->toArray();
    }
    
    /**
     * @param array $filters See function search $parameters['filters']
     * @return int Count of rows that match $filters
     */
    public function count(array $filters = array()) {
        
        $select = $this->select();
        
        $this->processFilters($filters, $select);
        
		
        $select->reset('columns');
		
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
                case 'name':
                case 'about':
                case 'order_number':
                case 'status':
                    if (is_array($value)) {
                        $select->where($field . ' IN (?)', $value);
                    } else {
                        $select->where($field . ' = ?', $value);
                    }
                    break;

                case 'name_search':
                    $select->where('name LIKE ?', '%' . $value . '%');
                    break;

                case 'about_search':
                    $select->where('about LIKE ?', '%' . $value . '%');
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

