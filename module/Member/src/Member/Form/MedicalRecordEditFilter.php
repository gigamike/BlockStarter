<?php

namespace Member\Form;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;

class MedicalRecordEditFilter extends InputFilter
{
  public function __construct(Adapter $dbAdapter)
  {
    $this->add([
      'name' => 'name',
      'required' => true,
      'filters' => [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
      ],
      'validators' => [
        [
          'name' => 'StringLength',
          'options' => [
            'encoding' => 'UTF-8',
            'min' => 1,
            'max' => 255,
          ],
        ],
      ],
    ]);

    $this->add([
      'name' => 'description',
      'required' => false,
      'filters' => [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
      ],
    ]);
  }
}
