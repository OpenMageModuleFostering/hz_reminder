<?php

class HZ_Reminder_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
	  parent::__construct();
	  $this->setId('reminderReportGrid');
	  $this->setUseAjax(true);
	  $this->setDefaultSort('id');
	  $this->setDefaultDir('ASC');
	  $this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
	  $collection = Mage::getModel('reminder/report')->getCollection();
	  $collection->getSelect()->group(array("report_date"));

	  $this->setCollection($collection);
	  return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{

        $this->addColumn('report_date',
        array(
            'header'=>Mage::helper('reminder')->__('Data'),
            'index'=>'report_date',
            'gmtoffset' => false,
            'type'=>'date',
            'format' => 'd-M-Y',
        ));

        $this->addColumn('total_send_mail', array(
            'filter'    => false,
            'sortable'  => false,
            'header'    => Mage::helper('reminder')->__('# Send Mail'),
            'align'     =>'center',
            'width'     => '100px',
            'frame_callback' => array($this, 'callback_total_send_mail')
        ));

        $this->addColumn('total_subscriber', array(
            'filter'    => false,
            'sortable'  => false,
            'header'    => Mage::helper('reminder')->__('# Subscribers'),
            'align'     =>'center',
            'width'     => '100px',
            'frame_callback' => array($this, 'callback_total_subscriber')
        ));

        return parent::_prepareColumns();
	}

    public function callback_total_send_mail($value, $row, $column, $isExport)
    {
        $report_date = $row->getReportDate();
        $collection = Mage::getModel('reminder/report')->getCollection();
        $collection->getSelect()->where("report_date='".$report_date."'")->group(array("report_date"));

        $send_total =  $collection->getSize();
        return $send_total;
    }

    public function callback_total_subscriber($value, $row, $column, $isExport)
    {
        $report_date = $row->getReportDate();
        $collection = Mage::getModel('reminder/reminder')->getCollection();
        $collection->getSelect()->where("reminder_date='".$report_date."'")->group(array("reminder_date"));

        $send_total =  $collection->getSize();
        return $send_total;
    }

}