<?php

class Application_Model_DbTable_CmsLanguages extends Zend_Db_Table_Abstract
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    protected $_name = 'cms_languages';
    
    /** 
     * @param int $id
     * @return null|array Associative array with keys as cms_languages table columns or NULL if not found
     */
    public function getLanguageById ($id) {
        
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
     * @param array $language Associative array with keys as column names and values as column new values
     */
    public function updateLanguage ($id, $language) {
        
        if (isset($language['id'])) {
            // Forbid changing of user id
            unset($language['id']);
        }
        
        $this->update($language, 'id = ' . $id);
    }
    
    /**
     * @param array $language Associative array with keys as column names and values as column new values
     * @return int The ID of new language (autoincrement)
     */
    public function insertLanguage ($language) {
        // fetch order number for new language
        
        $select = $this->select();
        
        $select->order('order_number DESC');
        
        $languageWithBiggerstOrderNumber = $this->fetchRow($select);
        
        if ($languageWithBiggerstOrderNumber instanceof Zend_Db_table_Row) {
            
            $language['order_number'] = $languageWithBiggerstOrderNumber['order_number'] + 1;
            
        } else {
			
            $language['order_number'] = 1;
        }
        
        $id = $this->insert($language);
        
        return $id;
    }
    
    /**
     * @param int $id ID of language to delete
     */
    public function deleteLanguage ($id) {
        
        $languagePhotoFilePath = PUBLIC_PATH . '/uploads/languages/' . $id . '.jpg';
        if(is_file($languagePhotoFilePath)) {
			
            unlink($languagePhotoFilePath);
        }
        
        $language = $this->getLanguageById($id);
        
        $this->update(array(
           'order_number' => new Zend_Db_Expr('order_number - 1') 
        ),
        'order_number > ' . $language['order_number']);
        
        $this->delete('id = ' . $id);
    }
    
    /**
     * @param int $id ID of language to disable
     */
    public function disableLanguage ($id) {
        
        $this->update(array(
            'status' => self::STATUS_DISABLED
        ), 'id = ' . $id);
    }
    
    /**
     * @param int $id ID of language to enable
     */
    public function enableLanguage ($id) {
        
        $this->update(array(
            'status' => self::STATUS_ENABLED
        ), 'id = ' . $id);
    }
    
    public function updateOrderOfLanguages ($sortedIds) {
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

