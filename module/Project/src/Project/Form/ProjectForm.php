<?php

namespace Project\Form;

use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

use Project\Form\ProjectFilter;

class ProjectForm extends Form
{
	public function __construct(Adapter $dbAdapter)
	{
		parent::__construct('create-project');
    $this->setInputFilter(new ProjectFilter($dbAdapter));
    $this->setAttribute('method', 'post');
    $this->setAttribute('enctype', 'multipart/form-data');
    $this->setHydrator(new ClassMethods());

		$this->add(array(
	    'name' => 'name',
	    'type' => 'Text',
	    'attributes' => array(
				'class' => 'form-control',
        'id' => 'name',
        'placeholder' => 'My Idea Project Name',
        'aria-label' => 'My Idea Project Name',
        'required' => 'required',
        'data-msg' => 'Please enter your project name.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
				'label' => 'Project Name <span class="text-danger">*</span>',
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
			 'placeholder' => 'Your awesome project description...',
			 'aria-label' => 'Your awesome project description...',
			 'required' => 'required',
			 'data-msg' => 'Please enter a project description.',
			 'data-error-class' => 'u-has-error',
			 'data-success-class' => 'u-has-success',
		 	],
			'options' => array(
				'label' => 'Project Description <span class="text-danger">*</span>',
        'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
			),
		));

		$this->add(array(
	    'name' => 'minimum_contribution',
	    'type' => 'Text',
	    'attributes' => array(
				'class' => 'form-control',
        'id' => 'minimum_contribution',
        'placeholder' => 'Minimum Contribution (ETH)',
        'aria-label' => 'Minimum Contribution (ETH)',
        'required' => 'required',
        'data-msg' => 'Please enter your minimum contribution (ETH).',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
				'label' => 'Minimum Contribution (ETH)<span class="text-danger">*</span>',
        'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
	    ),
		));

		$this->add(array(
	    'name' => 'photo',
	    'attributes' => array(
        'type'  => 'file',
        'id' => 'photo',
				'aria-label' => 'Project Photo',
        'required' => 'required',
        'data-msg' => 'Please enter your project photo.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
        'label' => 'Photo',
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
