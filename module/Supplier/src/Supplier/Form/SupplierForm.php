<?php

namespace Supplier\Form;

use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

use Supplier\Form\SupplierFilter;

class SupplierForm extends Form
{
	public function __construct(Adapter $dbAdapter)
	{
		parent::__construct('create-project');
    $this->setInputFilter(new SupplierFilter($dbAdapter));
    $this->setAttribute('method', 'post');
    $this->setAttribute('enctype', 'multipart/form-data');
    $this->setHydrator(new ClassMethods());

		$this->add(array(
	    'name' => 'name',
	    'type' => 'Text',
	    'attributes' => array(
				'class' => 'form-control',
        'id' => 'name',
        'placeholder' => 'Supplier Name',
        'aria-label' => 'Supplier Name',
        'required' => 'required',
        'data-msg' => 'Please enter your supplier name.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
				'label' => 'Supplier Name <span class="text-danger">*</span>',
        'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
	    ),
		));

		$this->add(array(
			'name' => 'description',
			'type' => 'Textarea',
			'attributes' => [
			 'class' => 'form-control',
			 'rows' => 4,
			 'id' => 'description',
			 'placeholder' => 'What do you sell...',
			 'aria-label' => 'Supplier Description',
			 'required' => 'required',
			 'data-msg' => 'What do you sell...',
			 'data-error-class' => 'u-has-error',
			 'data-success-class' => 'u-has-success',
		 	],
			'options' => array(
				'label' => 'Supplier Description <span class="text-danger">*</span>',
        'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
			),
		));

		$this->add(array(
	    'name' => 'bir_certificate',
	    'attributes' => array(
        'type'  => 'file',
        'id' => 'bir_certificate',
				'aria-label' => 'BIR Certificate',
        'required' => 'required',
        'data-msg' => 'Please enter BIR Certificate photo.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
        'label' => 'BIR Certificate',
				'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
	    ),
		));

		$this->add(array(
	    'name' => 'mayors_permit',
	    'attributes' => array(
        'type'  => 'file',
        'id' => 'mayors_permit',
				'aria-label' => 'Mayor\'s Permit',
        'required' => 'required',
        'data-msg' => 'Please enter Mayor\'s Permit photo.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
        'label' => 'Mayor\'s Permit',
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
}
