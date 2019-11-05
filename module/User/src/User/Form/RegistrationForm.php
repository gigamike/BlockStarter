<?php

namespace User\Form;

use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

use User\Form\RegistrationFilter;

class RegistrationForm extends Form
{
  public function __construct(Adapter $dbAdapter, $countryMapper)
  {
    parent::__construct('registration');
    $this->setInputFilter(new RegistrationFilter($dbAdapter));
    $this->setAttribute('method', 'post');
    $this->setAttribute('enctype', 'multipart/form-data');
    $this->setHydrator(new ClassMethods());

    $this->add([
      'name' => 'role',
      'type' => 'Select',
      'options' => [
        'label' => 'Role',
      ],
      'attributes' => [
        'class' => 'form-control',
        'id' => 'role',
        'required' => 'required',
        'autofocus' => 'autofocus',
        'options' => [
          '' => 'Select Role',
          'member' => 'Member',
          'supplier' => 'Supplier',
        ],
      ],
    ]);

    $this->add([
      'name' => 'first_name',
      'type' => 'text',
      'options' => [
        'label' => 'First Name',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'first_name',
        'required' => 'required',
        'placeholder' => 'First Name',
      ],
    ]);

    $this->add([
      'name' => 'last_name',
      'type' => 'text',
      'options' => [
        'label' => 'Last Name',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'last_name',
        'required' => 'required',
        'placeholder' => 'Last Name',
      ],
    ]);

    $this->add([
      'name' => 'email',
      'type' => 'email',
      'options' => [
        'label' => 'Email Address',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'email',
        'required' => 'required',
        'placeholder' => 'Email Address',
      ],
    ]);

    $this->add([
      'name' => 'password',
      'type' => 'password',
      'options' => [
        'label' => 'Password',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'password',
        'required' => 'required',
        'placeholder' => 'Password',
      ],
    ]);

    $this->add([
      'name' => 'confirm_password',
      'type' => 'password',
      'options' => [
        'label' => 'Confirm password',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'confirm_password',
        'required' => 'required',
        'placeholder' => 'Confirm Password',
      ],
    ]);

    $this->add([
      'name' => 'city',
      'type' => 'text',
      'options' => [
        'label' => 'City',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'city',
        'placeholder' => 'City',
        'required' => 'required',
      ],
    ]);

    $this->add(array(
	    'name' => 'country_id',
	    'type' => 'Select',
	    'attributes' => array(
        'class' => 'form-control',
        'id' => 'country_id',
        'options' => $this->_getCountries($countryMapper),
        'required' => 'required',
	    ),
	    'options' => array(
        'label' => 'Country *',
	    ),
		));

    $this->add([
      'name' => 'company_name',
      'type' => 'text',
      'options' => [
        'label' => 'Company Name',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'company_name',
        'placeholder' => 'Company Name',
      ],
    ]);

    $this->add([
      'name' => 'company_description',
      'type' => 'textarea',
      'options' => [
        'label' => 'Company Description',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'company_description',
        'placeholder' => 'Company Description',
      ],
    ]);

    $this->add(array(
	    'name' => 'mayors_permit',
	    'attributes' => array(
        'type'  => 'file',
        'id' => 'photo',
				'aria-label' => 'Mayor\'s Permit',
        'data-msg' => 'Please enter Mayor\'s Permit.',
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
	    'name' => 'company_photo',
	    'attributes' => array(
        'type'  => 'file',
        'id' => 'company_photo',
				'aria-label' => 'Photo',
        'data-msg' => 'Please enter Company Photo.',
        'data-error-class' => 'u-has-error',
        'data-success-class' => 'u-has-success',
	    ),
	    'options' => array(
        'label' => 'Company Photo',
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
        'data-msg' => 'Please enter BIR Certificate.',
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

    $this->add([
      'name' => 'referred_by',
      'type' => 'text',
      'options' => [
        'label' => 'Referred By',
      ],
      'attributes' => [
        'class' => 'form-control form-control-user',
        'id' => 'referred_by',
        'placeholder' => 'Referred By',
        'readonly' => 'readonly',
      ],
    ]);

    $this->add(array(
			'name' => 'security',
			'type' => 'Csrf',
		));

		$this->add(array(
			'name' => 'captcha',
			'type' => 'Captcha',
			'attributes' => array(
				'class' => 'form-control form-control-user',
				'id' => 'captcha',
				'placeholder'  => 'Please verify you are human.',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Security Code *',
				'captcha' => array(
					'class' => 'Dumb',
	        'wordLen' => 3,
				),
			),
		));

    $this->add([
      'name' => 'submit',
      'type' => 'submit',
      'attributes' => [
        'value' => 'Register Account',
        'class' => 'btn btn-primary btn-user btn-block',
      ],
    ]);
  }

  private function _getCountries($countryMapper){
    $countries = array(
      '' => 'Select Country',
    );
    $filter = array();
    $order = array(
      'country_name',
    );
    $temp = $countryMapper->fetch(false, $filter, $order);
    foreach ($temp as $country){
      $countries[$country->getId()] = $country->getCountryName();
    }

    return $countries;
	}
}
