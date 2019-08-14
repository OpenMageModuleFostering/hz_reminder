<?php
class HZ_Reminder_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_report';
    $this->_blockGroup = 'reminder';
    $this->_headerText = Mage::helper('reminder')->__('Reminder Report');
    parent::__construct();

    $this->_removeButton('add');
  }
}