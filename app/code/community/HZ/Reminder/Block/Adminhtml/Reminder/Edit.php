<?php

class HZ_Reminder_Block_Adminhtml_Reminder_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'reminder';
        $this->_controller = 'adminhtml_reminder';
        
        $this->_updateButton('save', 'label', Mage::helper('reminder')->__('Save Reminder'));
        $this->_updateButton('delete', 'label', Mage::helper('reminder')->__('Delete Reminder'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('reminder_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'reminder_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'reminder_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('reminder_data') && Mage::registry('reminder_data')->getId() ) {
            return Mage::helper('reminder')->__("Edit Reminder '%s'", $this->htmlEscape(Mage::registry('reminder_data')->getTitle()));
        }
        else {
            return Mage::helper('reminder')->__('Add Reminder');
        }
    }
}