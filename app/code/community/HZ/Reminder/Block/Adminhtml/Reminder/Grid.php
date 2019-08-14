<?php

class HZ_Reminder_Block_Adminhtml_Reminder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
	  parent::__construct();
	  $this->setId('reminderGrid');
	  $this->setUseAjax(true);
	  $this->setDefaultSort('id');
	  $this->setDefaultDir('ASC');
	  $this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
	  $collection = Mage::getModel('reminder/reminder')->getCollection();
	  $this->setCollection($collection);
	  return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
        $this->addColumn('id', array(
          'header'    => Mage::helper('reminder')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
        ));

        $this->addColumn('title', array(
          'header'    => Mage::helper('reminder')->__('Name'),
          'align'     =>'left',
          'index'     => 'title',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('reminder')->__('Email'),
            'align'     =>'left',
            'index'     => 'email',
        ));

        $this->addColumn('product_name', array(
            'header'    => Mage::helper('reminder')->__('Product Name'),
            'align'     =>'left',
            'index'     => 'product_name',
        ));

        $this->addColumn('reminder_date', array(
            'header'    => Mage::helper('reminder')->__('Reminder Data'),
            'align'     =>'left',
            'index'     => 'reminder_date',
            'format' => 'd-M-Y',
            'gmtoffset' => false,
            'type'=>'date'
        ));

        $this->addColumn('register_date',
        array(
            'header'=>Mage::helper('reminder')->__('Register Data'),
            'index'=>'register_date',
            'format' => 'd-M-Y',
            'gmtoffset' => false,
            'type'=>'date'
        ));

        $this->addColumn('ip', array(
            'header'    => Mage::helper('reminder')->__('IP'),
            'align'     =>'left',
            'width'     => '100px',
            'index'     => 'ip',
        ));

        $this->addColumn('status', array(
          'header'    => Mage::helper('reminder')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Subscribed',
              2 => 'Unsubscribed',
          ),
        ));

        return parent::_prepareColumns();
	}

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('reminder');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('reminder')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('reminder')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('reminder/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('reminder')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('reminder')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

}