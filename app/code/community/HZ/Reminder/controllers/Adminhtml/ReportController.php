<?php

class HZ_Reminder_Adminhtml_ReportController extends Mage_Adminhtml_Controller_action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('reminder/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Reminder Report'), Mage::helper('adminhtml')->__('Reminder Report'));
		
		return $this;
	}   
 
	public function indexAction() {
        $this->_title($this->__("Reminder"));
        $this->_title($this->__("Reminder Report"));

		$this->_initAction()
			->renderLayout();
	}

}