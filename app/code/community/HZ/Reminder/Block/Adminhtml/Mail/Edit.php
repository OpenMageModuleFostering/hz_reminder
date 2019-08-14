<?php

class HZ_Reminder_Block_Adminhtml_Mail_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'reminder';
        $this->_controller = 'adminhtml_mail';

        $this->_updateButton('save', 'label', Mage::helper('adminhtml')->__('Send mail'));
        $this->_removeButton('delete');
        $this->_removeButton('back');
    }

    public function getHeaderText()
    {
        return Mage::helper('reminder')->__('Send Reminder Mail');
    }
}