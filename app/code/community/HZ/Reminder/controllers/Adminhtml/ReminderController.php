<?php

class HZ_Reminder_Adminhtml_ReminderController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('reminder/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Reminder'), Mage::helper('adminhtml')->__('Manage Reminder'));
		
		return $this;
	}   
 
	public function indexAction() {

        $this->_title($this->__("Reminder"));
        $this->_title($this->__("Manager Reminder"));

		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {

        $this->_title($this->__("Reminder"));
        $this->_title($this->__("Reminder"));
        $this->_title($this->__("Edit Item"));

		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('reminder/reminder')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('reminder_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('reminder/items');

            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Reminder Manager"), Mage::helper("adminhtml")->__("Reminder Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Reminder Description"), Mage::helper("adminhtml")->__("Reminder Description"));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			
			$this->_addContent($this->getLayout()->createBlock('reminder/adminhtml_reminder_edit'))
				->_addLeft($this->getLayout()->createBlock('reminder/adminhtml_reminder_edit_tabs'));
			
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reminder')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('reminder/adminhtml_reminder_grid')->toHtml());
    }

    public function saveAction() {
        $model = Mage::getModel('reminder/reminder');
        if ($data = $this->getRequest()->getPost()) {
            try {

                $model = Mage::getModel('reminder/reminder');
                $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

                if ($model->getCreatedAt == NULL || $model->getUpdatedAt() == NULL) {
                    $model->setCreatedAt(now())
                        ->setUpdatedAt(now());

                    //IP Address
                    $ip = Mage::helper('core/http')->getRemoteAddr();
                    $model->setIp($ip);
                }
                else {
                    $model->setUpdatedAt(now());
                }

                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reminder')->__('Reminder was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }

                $this->_redirect('*/*/');
                return;

            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reminder')->__('Unable to find Reminder to save'));
        $this->_redirect('*/*/');
    }
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('reminder/reminder');
				$model->setId($this->getRequest()->getParam('id'))->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $reminderIds = $this->getRequest()->getParam('reminder');
        if(!is_array($reminderIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        }
        else {
            try {
                foreach ($reminderIds as $reminderId) {
                    $reminder = Mage::getModel('reminder/reminder')->load($reminderId);
                    $reminder->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($reminderIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $reminderIds = $this->getRequest()->getParam('reminder');
        if(!is_array($reminderIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($reminderIds as $reminderId) {
                    $reminder = Mage::getSingleton('reminder/reminder')
                        ->load($reminderId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($reminderIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'reminder.csv';
        $content    = $this->getLayout()->createBlock('reminder/adminhtml_reminder_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'reminder.xml';
        $content    = $this->getLayout()->createBlock('reminder/adminhtml_reminder_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}