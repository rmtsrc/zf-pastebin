<?php
// application/models/PastebinMapper.php

class Default_Model_PastebinMapper
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
            $this->setDbTable('Default_Model_DbTable_Pastebin');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_Pastebin $pastebin)
    {
        $shortId = $pastebin->getShortId();
        if (empty($shortId)) {
            $shortId = $this->_getShortId();
            $pastebin->setShortId($shortId);
        }
        $name = $pastebin->getName();

        $expiresTime = $pastebin->getExpires();
        $expires = null;
        if ($expiresTime != 'never') {
            $expires = new Zend_Date();
            if ($expiresTime == 'hour') $expires->addHour(1);
            if ($expiresTime == 'day') $expires->addDay(1);
            if ($expiresTime == 'week') $expires->addWeek(1);
            if ($expiresTime == 'month') $expires->addMonth(1);            
            $expires = $expires->get('yyyy-MM-dd HH:mm:ss');
        }        

        $data = array(
            'short_id'   => $shortId,
            'name'   => (!empty($name)) ? $name : 'Anonymous',
            'code' => $pastebin->getCode(),
            'language' => $pastebin->getLanguage(),
            'expires'   => $expires,
            'ip_address'   => $_SERVER['REMOTE_ADDR'],
            'created' => date('Y-m-d H:i:s'),
        );

        if (null === ($id = $pastebin->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }

        return $shortId;
    }

    private function _getShortId() {
        $db = $this->getDbTable();
        $dbInfo = $db->info();

        $select = $db->select();

        $shortId = $this->_genShortId();
        $existing = $select->where('short_id = ?', $shortId)->query()->fetchAll();
        if (!empty($existing)) {
            die('already');
            return $this->_getShortId();
        }

        return $shortId;
    }

    private function _genShortId($numAlpha=6)
    {
       $listAlpha = 'abcdefghijklmnopqrstuvwxyz0123456789';
       return str_shuffle(
          substr(str_shuffle($listAlpha),0,$numAlpha)
      );
    }

    public function find($id, Default_Model_Pastebin $pastebin)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $this->_setObject($row, $pastebin);
    }

    public function findShortId($shortId, Default_Model_Pastebin $pastebin)
    {
        $row = $this->getDbTable()->findShortId($shortId);
        if (!empty($row)) {
            $this->_setObject($row, $pastebin);
        }
    }

    private function _setObject($row, Default_Model_Pastebin $pastebin)
    {
        $pastebin->setId($row->id)
                  ->setShortId($row->short_id)
                  ->setName($row->name)
                  ->setLanguage($row->language)
                  ->setCode($row->code)
                  ->setIpAddress($row->ip_address)
                  ->setCreated($row->created);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll(null, 'created DESC');
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_Pastebin();
            $entry->setId($row->id)
                  ->setShortId($row->short_id)
                  ->setName($row->name)
                  ->setLanguage($row->language)
                  ->setCode($row->code)
                  ->setIpAddress($row->ip_address)
                  ->setCreated($row->created)
                  ->setMapper($this);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function delete($id, $column = 'id')
    {
        $dbAdapter = $this->getDbTable()->getAdapter();
        $where = $dbAdapter->quoteInto($dbAdapter->quoteIdentifier($column).' = ?', $id);
        return $this->getDbTable()->delete($where);
    }

    public function getPastebin($id = null)
    {
        $db = $this->getDbTable();
        $dbInfo = $db->info();

        $select = $db->select()->order('created DESC');

        if (!is_null($id)) {
            $select->where('short_id = ?', $id);
            $select->limit(1);
        }
        $select->where('(expires IS NULL) OR (expires > ?)', date('Y-m-d H:i:s'));

        //var_dump((string) $select);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $paginator = new Zend_Paginator($adapter);
       
        return $paginator;
    }
}
