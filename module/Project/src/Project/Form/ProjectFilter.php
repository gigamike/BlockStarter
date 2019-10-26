<?php

namespace Project\Form;

use Zend\InputFilter\InputFilter;

class ProjectFilter extends InputFilter
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
      'name' => 'minimum_contribution',
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
	    'name' => 'photo',
	    'required' => false,
		));
	}
}
