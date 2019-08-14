<?php

class HZ_Reminder_Block_Adminhtml_Reminder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('reminder_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('reminder')->__('Reminder Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('reminder')->__('Reminder Information'),
          'title'     => Mage::helper('reminder')->__('Reminder Information'),
          'content'   => $this->getLayout()->createBlock('reminder/adminhtml_reminder_edit_tab_form')->toHtml(),
      ));  
      return parent::_beforeToHtml();
  }
}