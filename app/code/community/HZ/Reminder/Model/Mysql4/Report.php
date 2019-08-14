<?php

class HZ_Reminder_Model_Mysql4_Report extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the reminder_id refers to the key field in your database table.
        $this->_init('reminder/report', 'id');
    }
}