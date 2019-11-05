<?php

namespace User\Form;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;

class RegistrationFilter extends InputFilter
{
  public function __construct(Adapter $dbAdapter)
  {
    $this->add(array(
	    'name' => 'role',
	    'required' => true,
	    'validators' => array(
        array(
          'name' => 'InArray',
          'options' => array(
            'haystack' => array('admin', 'member', 'supplier'),
          ),
        ),
	    ),
		));

    $this->add([
      'name' => 'first_name',
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
      'name' => 'last_name',
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
      'name' => 'email',
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
            'max' => 100,
          ],
        ],
        [
          'name' => 'EmailAddress',
          'options' => [
            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
            'useMxCheck' => false,
          ],
        ],
        [
          'name' => 'Zend\Validator\Db\NoRecordExists',
          'options' => [
            'adapter' => $dbAdapter,
            'table' => 'user',
            'field' => 'email',
          ],
        ],
      ],
    ]);

    $this->add([
      'name' => 'password',
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
            'min' => 5,
            'max' => 255,
          ],
        ],
        [
          'name'    => 'Identical',
          'options' => [
            'token' => 'confirm_password',
          ],
        ],
      ],
    ]);

    $this->add([
      'name' => 'confirm_password',
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
            'min' => 5,
            'max' => 255,
          ],
        ],
      ],
    ]);

    $this->add([
      'name' => 'city',
      'required' => false,
      'filters' => [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
      ],
      'validators' => [
        [
          'name' => 'StringLength',
          'options' => [
            'encoding' => 'UTF-8',
            'min' => 5,
            'max' => 255,
          ],
        ],
      ],
    ]);

    $this->add([
      'name' => 'country_id',
      'required' => true,
    ]);

    $this->add([
      'name' => 'company_name',
      'required' => false,
      'filters' => [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
      ],
      'validators' => [
        [
          'name' => 'StringLength',
          'options' => [
            'encoding' => 'UTF-8',
            'min' => 5,
            'max' => 255,
          ],
        ],
      ],
    ]);

    $this->add([
      'name' => 'company_description',
      'required' => false,
      'filters' => [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
      ],
      'validators' => [
        [
          'name' => 'StringLength',
          'options' => [
            'encoding' => 'UTF-8',
            'min' => 5,
            'max' => 255,
          ],
        ],
      ],
    ]);

    $this->add(array(
	    'name' => 'company_photo',
	    'required' => false,
		));

    $this->add(array(
	    'name' => 'mayors_permit',
	    'required' => false,
		));

    $this->add(array(
	    'name' => 'bir_certificate',
	    'required' => false,
		));

    $this->add(array(
	    'name' => 'referred_by',
	    'required' => false,
		));
  }
}
