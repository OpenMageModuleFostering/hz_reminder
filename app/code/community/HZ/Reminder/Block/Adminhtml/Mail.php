<?php
class HZ_Reminder_Block_Adminhtml_Mail extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_mail';
    $this->_blockGroup = 'reminder';
    $this->_headerText = Mage::helper('reminder')->__('Reminder Mail Form');
    $this->_addButtonLabel = Mage::helper('reminder')->__('Add New Reminder');
    parent::__construct();
  }
}