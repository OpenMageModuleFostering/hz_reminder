<?php
class HZ_Reminder_Block_Adminhtml_Reminder extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_reminder';
    $this->_blockGroup = 'reminder';
    $this->_headerText = Mage::helper('reminder')->__('Reminder Subscribers');
    $this->_addButtonLabel = Mage::helper('reminder')->__('Add New Reminder');
    parent::__construct();

    $this->_removeButton('add');
  }
}