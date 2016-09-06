<?php

class Application_Model_DbTable_CmsLetters extends Zend_Db_Table_Abstract
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    protected $_name = 'cms_letters';
    
    /** 
     * @param int $id
     * @return null|array Associative array with keys as cms_letters table columns or NULL if not found
     */
    public function getLetterById ($id) {
        
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
     * @param array $letter Associative array with keys as column names and values as column new values
     */
    public function updateLetter ($id, $letter) {
        
        if (isset($letter['id'])) {
            // Forbid changing of user id
            unset($letter['id']);
        }
        
        $this->update($letter, 'id = ' . $id);
    }
    
    /**
     * @param array $letter Associative array with keys as column names and values as column new values
     * @return int The ID of new letter (autoincrement)
     */
    public function insertLetter ($letter) {
        // fetch order number for new letter
        
        $select = $this->select();
        
        $select->order('order_number DESC');
        
        $letterWithBiggerstOrderNumber = $this->fetchRow($select);
        
        if ($letterWithBiggerstOrderNumber instanceof Zend_Db_table_Row) {
            
            $letter['order_number'] = $letterWithBiggerstOrderNumber['order_number'] + 1;
            
        } else {
			
            $letter['order_number'] = 1;
        }
        
        $id = $this->insert($letter);
        
        return $id;
    }
    
    /**
     * @param int $id ID of letter to delete
     */
    public function deleteLetter ($id) {
        
        $letterPhotoFilePath = PUBLIC_PATH . '/uploads/letters/' . $id . '.jpg';
        if(is_file($letterPhotoFilePath)) {
			
            unlink($letterPhotoFilePath);
        }
        
        $letter = $this->getLetterById($id);
        
        $this->update(array(
           'order_number' => new Zend_Db_Expr('order_number - 1') 
        ),
        'order_number > ' . $letter['order_number']);
        
        $this->delete('id = ' . $id);
    }
    
    /**
     * @param int $id ID of letter to disable
     */
    public function disableLetter ($id) {
        
        $this->update(array(
            'status' => self::STATUS_DISABLED
        ), 'id = ' . $id);
    }
    
    /**
     * @param int $id ID of letter to enable
     */
    public function enableLetter ($id) {
        
        $this->update(array(
            'status' => self::STATUS_ENABLED
        ), 'id = ' . $id);
    }
    
    public function updateOrderOfLetters ($sortedIds) {
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
                    case 'title':
                    case 'isbn':
                    case 'category_id':
                    case 'author_id':
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
				case 'title':
				case 'isbn':
				case 'category_id':
				case 'author_id':
				case 'order_number':
				case 'status':
                    if (is_array($value)) {
                        $select->where($field . ' IN (?)', $value);
                    } else {
                        $select->where($field . ' = ?', $value);
                    }
                    break;

                case 'title_search':
                    $select->where('title LIKE ?', '%' . $value . '%');
                    break;

                case 'isbn_search':
                    $select->where('isbn LIKE ?', '%' . $value . '%');
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

