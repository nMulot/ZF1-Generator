<?php
/**
 * Model_UsersMapper.php
 * @author HUGOCORP DR <contact@hugocorp.com>
 */
 
class Model_UsersMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Model_DbTable_Users');
        }
        return $this->_dbTable;
    }
    
    public function find($id, Model_Users $obj)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
            $obj->setId($row->id)
                ->setNom($row->nom)
                ->setPrenom($row->prenom)
                ->setLogin($row->login)
                ->setPass($row->pass)
                ->setEmail($row->email)
                ->setActif($row->actif)
                ->setLast_connect($row->last_connect);
    }

    public function fetchAll($where=null, $order=null, $limit=null)
    {
        $select = $this->getDbTable()->select();
        if ($where != null) {
            if (is_array($where)) {
                foreach ($where as $condition => $value) {
                    if (is_numeric($condition)) {
                        $select->where($value);
                    } else {
                        $select->where($condition, $value);
                    }
                }
            } else {
                $select->where($where);
            }
        }
        if ($order != null) {
            $select->order($order);
        }
        if ($limit != null) {
            $select->limit($limit);
        }
        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries   = array();
        foreach ($resultSet as $row) {
            $obj = new Model_Users();
            $obj->info($row->id);
            $entries[] = $obj;
        }
        return $entries;
    }
    
    public function save(Model_Users $obj)
    {
        $data = array(
            'id' => $obj->getId(),
            'nom' => $obj->getNom(),
            'prenom' => $obj->getPrenom(),
            'login' => $obj->getLogin(),
            'pass' => $obj->getPass(),
            'email' => $obj->getEmail(),
            'actif' => $obj->getActif(),
            'last_connect' => $obj->getLast_connect()
        );
 
        if (null == ($id = $obj->getId())) {
            unset($data['id']);
            $id = $this->getDbTable()->insert($data);
        } else {
            $result = $this->getDbTable()->find($id);
            if (count($result)==0) {
                $id = $this->getDbTable()->insert($data);
            } else {
                $this->getDbTable()->update($data, array('id=?' => $id));
            }
        }
        
        return $id;
    }
    
    public function delete($id)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id=?', $id);
        $numrows = $table->delete($where);
        
        return $numrows;
    }
    
}