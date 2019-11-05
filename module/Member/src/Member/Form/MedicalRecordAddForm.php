<?php

namespace Member\Form;

use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

use Member\Form\MedicalRecordAddFilter;

class MedicalRecordAddForm extends Form
{
  public function __construct(Adapter $dbAdapter)
  {
    parent::__construct('medical-record-add');
    $this->setInputFilter(new MedicalRecordAddFilter($dbAdapter));
    $this->setAttribute('method', 'post');
    $this->setAttribute('enctype', 'multipart/form-data');
    $this->setHydrator(new ClassMethods());

    $this->add(array(
	    'name' => 'photo',
	    'attributes' => array(
        'type'  => 'file',
        'id' => 'photo',
				'aria-label' => 'Photo',
        'data-msg' => 'Please enter photo.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
        'required' => 'required',
	    ),
	    'options' => array(
        'label' => 'Medical Record',
				'label_attributes' => array(
          'class'  => 'form-label'
        ),
        'label_options' => array(
          'disable_html_escape' => true,
        ),
	    ),
		));

    $this->add([
      'name' => 'submit',
      'type' => 'submit',
      'attributes' => [
        'value' => 'Submit',
        'class' => 'btn btn-primary',
      ],
    ]);
  }
}
