<?php
class HZ_Reminder_Model_Observer
{
    const XML_PATH_REMINDER_EMAIL_RECIPIENT  = 'reminder/email/sender_name';
    const XML_PATH_REMINDER_EMAIL_SENDER  = 'reminder/email/sender_email';
    const XML_PATH_REMINDER_EMAIL_SUBJECT  = 'reminder/email/subject';
    const XML_PATH_REMINDER_EMAIL_TEMPLATE  = 'reminder/email/subscriber_template';
    const XML_PATH_REMINDER_EMAIL_REMINDER_DATE  = 'reminder/email/remainder_date';

    public function scheduledSendEmailReminder(){

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);
        try {
            //START: Subscriber
            $storeId = Mage::app()->getStore()->getId();
            $report_date = date('Y-m-d');
            $report_date_and_time = date('Y-m-d H:i:s');

            $emailTemplate = Mage::getModel('core/email_template');

            $emailTemplate->setDesignConfig(array(
                'area'  => 'frontend',
                'store' => $storeId
            ));

            $sender  = array(
                'name'  => Mage::helper('core')->escapeHtml(Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_RECIPIENT)),
                'email' => Mage::helper('core')->escapeHtml(Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_SENDER))
            );

            $mailSubject = Mage::helper('core')->escapeHtml(Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_SUBJECT));

            //START: Subscriber
            $collection = Mage::getModel('reminder/reminder')->getCollection();

            //Filter Date Range
            $reminderDate = trim(Mage::getStoreConfig(self::XML_PATH_REMINDER_EMAIL_REMINDER_DATE));
            $reminderDateArray = explode(",", $reminderDate);

            $current_date = date('Y-m-d');
            $where = array();
            $where[] = "reminder_date='".$current_date."'";
            foreach ($reminderDateArray as $date) {
                $next_date = date('Y-m-d', strtotime($date . ' days', strtotime($current_date)));
                $where[] = "reminder_date='".$next_date."'";
            }

            $whereCondition = implode(" OR ", $where);

            $collection->getSelect()->where($whereCondition);

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
                $model->setProcessRun('cron');
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
        }
        catch (Exception $e) {
            $translate->setTranslateInline(true);
        }

    }

}
