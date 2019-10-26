<?php

namespace Project\Form;

use Zend\InputFilter\InputFilter;

class ProjectSpendingRequestFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
			'name' => 'description',
			'required' => true,
		));

		$this->add(array(
			'name' => 'supplier_id',
			'required' => true,
		));

		$this->add(array(
      'name' => 'amount',
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
	}
}
