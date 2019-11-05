<?php

namespace Member\Form;

use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

use Member\Form\MedicalRecordEditFilter;

class MedicalRecordEditForm extends Form
{
  public function __construct(Adapter $dbAdapter)
  {
    parent::__construct('medical-record-add');
    $this->setInputFilter(new MedicalRecordEditFilter($dbAdapter));
    $this->setAttribute('method', 'post');
    $this->setHydrator(new ClassMethods());

    $this->add([
      'name' => 'name',
      'type' => 'text',
      'options' => [
        'label' => 'Name',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'name',
        'required' => 'required',
        'placeholder' => 'Name',
      ],
    ]);

    $this->add([
      'name' => 'description',
      'type' => 'textarea',
      'options' => [
        'label' => 'Description',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'description',
        'required' => 'required',
        'placeholder' => 'Description',
      ],
    ]);

    $this->add([
      'name' => 'tags',
      'type' => 'textarea',
      'options' => [
        'label' => 'Tags',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'tags',
        'data-role' => 'tagsinput',
      ],
    ]);

    $this->add([
      'name' => 'request_by',
      'type' => 'Select',
      'options' => [
        'label' => 'Requested By',
      ],
      'attributes' => [
        'class' => 'form-control',
        'id' => 'role',
        'options' => [
          '' => '',
          '1' => 'Dr. Ace Mark Llentada',
          '2' => 'Dra. Amah Buenaventura',
          '3' => 'Dr. Joel Solante',
          '4' => 'Dr. Tom Chua',
          '5' => 'Dr. Von Payumo',
        ],
      ],
    ]);

    $this->add([
      'name' => 'hospital',
      'type' => 'Select',
      'options' => [
        'label' => 'Hospital',
      ],
      'attributes' => [
        'class' => 'form-control',
        'id' => 'role',
        'options' => [
          '' => '',
          '1' => 'Medical Center Paranaque',
          '2' => 'Olivarez General Hospital',
          '3' => 'Ospital ng Parañaque',
          '4' => 'Our Lady of Peace Hospital',
          '5' => 'Protacio Medical Services',
          '6' => 'South Superhighway Medical Center',
          '7' => 'Sta. Rita de Baclaran Hospital',
          '8' => 'Sto. Niño de Cebu Maternity Hospital',
          '9' => 'UHBI - Parañaque Doctors\' Hospital',
          '10' => 'Unihealth Paranaque Hospital and Medical Center',
        ],
      ],
    ]);

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
