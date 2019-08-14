<?php

class HZ_Reminder_Model_Mysql4_Report_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('reminder/report');
    }
}