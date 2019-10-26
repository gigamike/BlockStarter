<?php

namespace Project\Form;

use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

use Project\Form\ProjectFilter;

class ProjectSpendingRequestForm extends Form
{
	public function __construct(Adapter $dbAdapter, $supplierMapper)
	{
		parent::__construct('project-spending-request');
    $this->setInputFilter(new ProjectSpendingRequestFilter($dbAdapter));
    $this->setAttribute('method', 'post');
    $this->setHydrator(new ClassMethods());

		$this->add(array(
			'name' => 'description',
			'type' => 'Textarea',
			'attributes' => [
			 'class' => 'form-control',
			 'rows' => 4,
			 'id' => 'description',
			 'placeholder' => 'Spending request description...',
			 'aria-label' => 'Spending request description...',
			 'required' => 'required',
			 'data-msg' => 'Please enter a project description.',
			 'data-error-class' => 'u-has-error',
			 'data-success-class' => 'u-has-success',
		 	],
			'options' => array(
				'label' => 'Spending request description <span class="text-danger">*</span>',
        'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
			),
		));

		$this->add(array(
	    'name' => 'supplier_id',
	    'type' => 'Select',
	    'attributes' => array(
        'class' => 'form-control',
        'id' => 'supplier_id',
        'options' => $this->_getSuppliers($supplierMapper),
        'aria-label' => 'Select supplier',
        'required' => 'required',
        'data-msg' => 'Please select supplier.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
			'options' => array(
				'label' => 'Supplier <span class="text-danger">*</span>',
        'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
	    ),
		));

		$this->add(array(
	    'name' => 'amount',
	    'type' => 'Text',
	    'attributes' => array(
				'class' => 'form-control',
        'id' => 'amount',
        'placeholder' => 'PEOS Amount',
        'aria-label' => 'PEOS Amount',
        'required' => 'required',
        'data-msg' => 'Please enter PEOS amount.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
				'label' => 'PEOS Amount <span class="text-danger">*</span>',
        'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
	    ),
		));

		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class' => 'btn btn-primary',
				'value' => 'Submit',
			),
		));
	}

	private function _getSuppliers($supplierMapper){
    $temp = array(
			'' => 'Select Supplier',
    );

		$order = array('name');
    $suppliers = $supplierMapper->fetch(false, null, $order);
    foreach ($suppliers as $supplier){
      $temp[$supplier->getId()] = $supplier->getName() . " - " . $supplier->getEosPublicAddress();
    }

    return $temp;
	}
}
