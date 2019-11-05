<?php

namespace Member\Form;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;

class MedicalRecordAddFilter extends InputFilter
{
  public function __construct(Adapter $dbAdapter)
  {
    $this->add(array(
	    'name' => 'photo',
	    'required' => false,
		));
  }
}
