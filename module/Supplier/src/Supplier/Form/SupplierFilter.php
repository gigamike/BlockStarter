<?php

namespace Supplier\Form;

use Zend\InputFilter\InputFilter;

class SupplierFilter extends InputFilter
{
	public function __construct()
	{
    $this->add(array(
      'name' => 'name',
      'required' => true,
      'filters' => array(
        array(
          'name' => 'StripTags',
        ),
      ),
      'validators' => array(
        array(
          'name' => 'StringLength',
          'options' => array(
            'encoding' => 'UTF-8',
            'min' => 1,
            'max' => 255,
          ),
        ),
      ),
    ));

		$this->add(array(
			'name' => 'description',
			'required' => true,
		));

		$this->add(array(
	    'name' => 'bir_certificate',
	    'required' => false,
		));

		$this->add(array(
	    'name' => 'mayors_permit',
	    'required' => false,
		));
	}
}
