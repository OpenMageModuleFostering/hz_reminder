<?php

class HZ_Reminder_Block_Adminhtml_Mail_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('mail_form', array('legend'=>Mage::helper('reminder')->__('Reminder Mail Form')));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('reminder_date_start', 'date', array(
            'name'   => 'reminder_date_start',
            'label'  => Mage::helper('reminder')->__('Reminder Data Start'),
            'title'  => Mage::helper('reminder')->__('Reminder Data Start'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'required'  => true
        ));

        $fieldset->addField('reminder_date_end', 'date', array(
            'name'   => 'reminder_date_end',
            'label'  => Mage::helper('reminder')->__('Reminder Data End'),
            'title'  => Mage::helper('reminder')->__('Reminder Data End'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'required'  => true
        ));

        if ( Mage::getSingleton('adminhtml/session')->getReminderData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getReminderData());
            Mage::getSingleton('adminhtml/session')->setReminderData(null);
        } elseif ( Mage::registry('reminder_data') ) {
            $form->setValues(Mage::registry('reminder_data')->getData());
        }
        return parent::_prepareForm();
    }
}