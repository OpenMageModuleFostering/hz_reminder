<?php

class HZ_Reminder_Block_Adminhtml_Reminder_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('reminder_form', array('legend'=>Mage::helper('reminder')->__('Reminder information')));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('reminder')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));

        $fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('reminder')->__('Email'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'email',
        ));

        $fieldset->addField('product', 'text', array(
            'label'     => Mage::helper('reminder')->__('Product'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'product',
        ));

        $fieldset->addField('product_url', 'text', array(
            'label'     => Mage::helper('reminder')->__('Product URL'),
            'required'  => false,
            'name'      => 'product_url',
        ));


        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('register_date', 'date', array(
            'name'   => 'register_date',
            'label'  => Mage::helper('reminder')->__('Register Data'),
            'title'  => Mage::helper('reminder')->__('Register Data'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'required'  => true
        ));

        $fieldset->addField('reminder_date', 'date', array(
            'name'   => 'reminder_date',
            'label'  => Mage::helper('reminder')->__('Reminder Data'),
            'title'  => Mage::helper('reminder')->__('Reminder Data'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'required'  => true
        ));

        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('reminder')->__('Status'),
            'name'      => 'status',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('reminder')->__('Subscribed'),
                ),

                array(
                    'value'     => 2,
                    'label'     => Mage::helper('reminder')->__('Unsubscribed'),
                ),
            ),
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