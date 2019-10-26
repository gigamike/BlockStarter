<?php

namespace User\Form;

use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

use User\Form\RegistrationFilter;

class RegistrationForm extends Form
{
    public function __construct(Adapter $dbAdapter)
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
		            'supplier' => 'Supplier',
                'engineer' => 'Engineer',
		            'manager-operation' => 'Operation Manager',
                'manager-finance' => 'Finance Manager',
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
            'name' => 'mobile_no',
            'type' => 'text',
            'options' => [
                'label' => 'Mobile No',
            ],
            'attributes' => [
                'class' => 'form-control form-control-user',
                'id' => 'mobile_no',
                'required' => 'required',
                'placeholder' => 'Mobile No',
            ],
        ]);

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
            'name' => 'company_address',
            'type' => 'textarea',
            'options' => [
                'label' => 'Company Address',
            ],
            'attributes' => [
                'class' => 'form-control form-control-user',
                'id' => 'company_address',
                'placeholder' => 'Company Address',
            ],
        ]);

        $this->add(array(
    	    'name' => 'company_logo',
    	    'attributes' => array(
            'type'  => 'file',
            'id' => 'company_logo',
    				'aria-label' => 'Company Logo',
            'data-msg' => 'Please enter Company Logo photo.',
            'data-error-class' => 'u-has-error',
            'data-success-class' => 'u-has-success',
    	    ),
    	    'options' => array(
            'label' => 'Company Logo',
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
            'data-msg' => 'Please enter BIR Certificate photo.',
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

    		$this->add(array(
    	    'name' => 'mayors_permit',
    	    'attributes' => array(
            'type'  => 'file',
            'id' => 'mayors_permit',
    				'aria-label' => 'Mayor\'s Permit',
            'data-msg' => 'Please enter Mayor\'s Permit photo.',
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
}
