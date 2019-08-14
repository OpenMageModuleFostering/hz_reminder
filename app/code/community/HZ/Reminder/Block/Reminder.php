<?php
class HZ_Reminder_Block_Reminder extends Mage_Core_Block_Template
{
	public function getReminder()
	{
			if (!$this->hasData('reminder')) {
					$this->setData('reminder', Mage::registry('reminder'));
			}
			return $this->getData('reminder');
	}

    public function getFormAction()
    {
        return $this->getUrl('*/*/post');
    }
}