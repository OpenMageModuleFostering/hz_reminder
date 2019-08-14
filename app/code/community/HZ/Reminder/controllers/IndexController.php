<?php
class HZ_Reminder_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();

        $this->getLayout()->getBlock('head')->setTitle($this->__('Reminder'));

        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home Page"),
            "title" => $this->__("Home Page"),
            "link"  => Mage::getBaseUrl()
        ));
        $breadcrumbs->addCrumb("reminder", array(
            "label" => $this->__("Reminder"),
            "title" => $this->__("Reminder")
        ));


        $this->_initLayoutMessages('customer/session');
		$this->renderLayout();
    }

    public function ajaxAction()
    {
        $this->loadLayout();

        $productSku = $_GET['sku'];

        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $productSku);
        $product_name = Mage::helper('core')->escapeHtml($product->getName());
        $product_url = $product->getProductUrl();

        Mage::register('product_sku', $productSku);
        Mage::register('product_name', $product_name);
        Mage::register('product_url', $product_url);

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        if ($post) {
            try {

                $error = false;

                if (!Zend_Validate::is(trim($post['title']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['product_name']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['reminder_day']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['reminder_month']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['reminder_year']) , 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }

                //Save Data
                $model = Mage::getModel('reminder/reminder');
                $model->setData($post);


                //Reminder Date
                $reminder_date = $post['reminder_year'] . '-' . $post['reminder_month'] . '-' . $post['reminder_day'];
                $model->setReminderDate($reminder_date);

                //IP Address
                $ip = Mage::helper('core/http')->getRemoteAddr();
                $model->setIp($ip);

                //register date
                $model->setRegisterDate(now());

                //created date
                $model->setCreatedAt(now())
                    ->setUpdatedAt(now());

                $model->save();

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('reminder')->__('Your reminder was submitted successfully.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('reminder')->__('Unable to submit your request. Please, try again later.'));
                $this->_redirect('*/*/');
                return;
            }

        }
        else {
            $this->_redirect('*/*/');
        }
    }

    public function ajaxPostAction()
    {
        $post = $this->getRequest()->getPost();

        if ($post) {
            try {
                $error = false;

                if (!Zend_Validate::is(trim($post['title']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['product_sku']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['product_name']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['reminder_day']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['reminder_month']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['reminder_year']) , 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }

                //Save Data
                $model = Mage::getModel('reminder/reminder');
                $model->setData($post);

                //Reminder Date
                $reminder_date = $post['reminder_year'] . '-' . $post['reminder_month'] . '-' . $post['reminder_day'];
                $model->setReminderDate($reminder_date);

                //IP Address
                $ip = Mage::helper('core/http')->getRemoteAddr();
                $model->setIp($ip);

                //register date
                $model->setRegisterDate(now());

                //created date
                $model->setCreatedAt(now())
                    ->setUpdatedAt(now());

                $model->save();

               echo '1';
               exit();
            }
            catch (Exception $e) {
                echo '0';
                exit();
            }

        }
        else {
            echo '0';
            exit();
        }
    }

}