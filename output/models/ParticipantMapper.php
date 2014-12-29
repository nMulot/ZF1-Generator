<?php
/**
 * Model_ParticipantMapper.php
 * @author HUGOCORP DR <contact@hugocorp.com>
 */
 
class Model_ParticipantMapper
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
            $this->setDbTable('Model_DbTable_Participant');
        }
        return $this->_dbTable;
    }
    
    public function find($id, Model_Participant $obj)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
            $obj->setId($row->id)
                ->setDate_insert($row->date_insert)
                ->setNom($row->nom)
                ->setPrenom($row->prenom)
                ->setMail($row->mail)
                ->setTel_mobile($row->tel_mobile)
                ->setNb_invites($row->nb_invites)
                ->setMontant($row->montant)
                ->setMode_reglement($row->mode_reglement)
                ->setEncaisse($row->encaisse)
                ->setUsers_id($row->users_id)
                ->setEvent_id($row->event_id);
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
            $obj = new Model_Participant();
            $obj->info($row->id);
            $entries[] = $obj;
        }
        return $entries;
    }
    
    public function save(Model_Participant $obj)
    {
        $data = array(
            'id' => $obj->getId(),
            'date_insert' => $obj->getDate_insert(),
            'nom' => $obj->getNom(),
            'prenom' => $obj->getPrenom(),
            'mail' => $obj->getMail(),
            'tel_mobile' => $obj->getTel_mobile(),
            'nb_invites' => $obj->getNb_invites(),
            'montant' => $obj->getMontant(),
            'mode_reglement' => $obj->getMode_reglement(),
            'encaisse' => $obj->getEncaisse(),
            'users_id' => $obj->getUsers_id(),
            'event_id' => $obj->getEvent_id()
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