<?php
class HZ_Reminder_Adminhtml_MailController extends Mage_Adminhtml_Controller_action
{

    const XML_PATH_REMINDER_EMAIL_RECIPIENT  = 'reminder/email/sender_name';
    const XML_PATH_REMINDER_EMAIL_SENDER  = 'reminder/email/sender_email';
    const XML_PATH_REMINDER_EMAIL_SUBJECT  = 'reminder/email/subject';
    const XML_PATH_REMINDER_EMAIL_TEMPLATE  = 'reminder/email/subscriber_template';
    const XML_PATH_REMINDER_EMAIL_REMINDER_DATE  = 'reminder/email/remainder_date';

	public function indexAction() {

        $this->_title($this->__("Reminder"));
        $this->_title($this->__("Reminder Mail Form"));

        Mage::register('reminder_data', '');

        $this->loadLayout();

        $this->_setActiveMenu('reminder/items');

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Reminder Mail Form"), Mage::helper("adminhtml")->__("Reminder Mail Form"));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

        $this->_addContent($this->getLayout()->createBlock('reminder/adminhtml_mail_edit'))
            ->_addLeft($this->getLayout()->createBlock('reminder/adminhtml_mail_edit_tabs'));

        $this->renderLayout();

	}

    public function sendMailAction(){
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }

        $post = $this->getRequest()->getPost();
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $error = false;

                if (!Zend_Validate::is(trim($post['reminder_date_start']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['reminder_date_end']) , 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }

                $storeId = Mage::app()->getStore()->getId();
                $report_date = date('Y-m-d');
                $report_date_and_time = date('Y-m-d H:i:s');

                $emailTemplate = Mage::getModel('core/email_template');

                $emailTemplate->setDesignConfig(array(
                    'area'  => 'frontend',
                    'store' => $storeId
                ));

                $sender  = array(
                    'name'  => $this->_getHelper()->escapeHtml(Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_RECIPIENT)),
                    'email' => $this->_getHelper()->escapeHtml(Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_SENDER))
                );

                $mailSubject = $this->_getHelper()->escapeHtml(Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_SUBJECT));

                //START: Subscriber
                $collection = Mage::getModel('reminder/reminder')->getCollection();

                //Filter Date Range
                $reminder_date_start = trim($post['reminder_date_start']);
                $reminder_date_end = trim($post['reminder_date_end']);
                $collection->addFieldToFilter('reminder_date', array(
                    'from' => $reminder_date_start,
                    'to' => $reminder_date_end,
                    'date' => true, // specifies conversion of comparison values
                ));

                $collection->load();

                foreach($collection->getItems() as $item) {
                    $subscriber_id = $item->getId();
                    $subscriber_name = $item->getTitle();
                    $subscriber_email = $item->getEmail();
                    $product_name = $item->getProductName();
                    $product_url = $item->getProductUrl();
                    $link = $item->getProductUrl();
                    $reminder_date = $item->getReminderDate();

                    $timestamp = strtotime($reminder_date);
                    $date = date("l, j  F  Y", $timestamp);

                    $emailTemplateVariables = array();
                    $emailTemplateVariables['subject'] = $mailSubject;
                    $emailTemplateVariables['subscriberName'] = $subscriber_name;
                    $emailTemplateVariables['productName'] = $product_name;
                    $emailTemplateVariables['reminderDate'] = $date;
                    $emailTemplateVariables['productURL'] = $product_url;

                    $emailTemplate->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_TEMPLATE),
                        $sender,
                        $subscriber_email,
                        $subscriber_name,
                        $emailTemplateVariables
                    );

                    //START: Save Report
                    $model = Mage::getModel('reminder/report');
                    $model->setSubscriberId($subscriber_id);
                    $model->setTitle($subscriber_name);
                    $model->setEmail($subscriber_email);
                    $model->setRemindDate($reminder_date);
                    $model->setReportDate($report_date);
                    $model->setReportDateAndTime($report_date_and_time);
                    $model->setProcessRun('admin');
                    //END: Save Report

                    if ($emailTemplate->getSentSuccess()) {
                        $model->setSendEmailStatus('yes');
                        $model->save();
                    }
                    else{
                        $model->setSendEmailStatus('no');
                        $model->save();
                    }
                }
                //END: Subscriber

                $translate->setTranslateInline(true);
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('reminder')->__('Reminder mail has been send successfully.'));
            }
            catch (Exception $e) {
                $translate->setTranslateInline(true);
                Mage::getSingleton('core/session')->addError(Mage::helper('reminder')->__('Unable to send reminder mail. Please, try again later.'));
            }

            $this->_redirectReferer();

        }

    }
}