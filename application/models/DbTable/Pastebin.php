<?php
// application/models/DbTable/Guestbook.php

/**
 * This is the DbTable class for the guestbook table.
 */
class Default_Model_DbTable_Pastebin extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'pastebin';

    public function findShortId($shortId)
    {
        $result = $this->select()->from($this->_name)->where('short_id = ?', $shortId)->limit(1)->query()->fetchObject();
        return (!empty($result) && is_object($result)) ? $result : false;
    }
}
