<?php

class HZ_Reminder_Block_Adminhtml_Mail_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('reminder_mail_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('reminder')->__('Reminder Mail Form'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('reminder')->__('Reminder Mail Form'),
          'title'     => Mage::helper('reminder')->__('Reminder Mail Form'),
          'content'   => $this->getLayout()->createBlock('reminder/adminhtml_mail_edit_tab_form')->toHtml(),
      ));  
      return parent::_beforeToHtml();
  }
}