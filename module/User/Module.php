<?php
namespace User;

use Zend\Authentication\AuthenticationService;
use User\Model\UserMapper;
use User\Form\RegistrationForm;
use User\Form\ForgotPasswordForm;
use Country\Model\CountryMapper;

class Module
{
    public function getConfig()
    {
      return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
      return array(
        'Zend\Loader\StandardAutoloader' => array(
          'namespaces' => array(
            __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
          ),
        ),
      );
    }

    public function getServiceConfig()
    {
    	return array(
  			'factories' => array(
          'auth_service' =>  function($sm) {
            $authService = new AuthenticationService();
            return $authService;
          },
					'UserMapper' => function ($sm) {
						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
						$mapper = new UserMapper($dbAdapter);
						return $mapper;
					},
          'RegistrationForm' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

            $countryMapper = new CountryMapper($dbAdapter);

            $form = new RegistrationForm($dbAdapter, $countryMapper);
            return $form;
          },
          'ForgotPasswordForm' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $form = new ForgotPasswordForm($dbAdapter);
            return $form;
          },
  			),
    	);
    }
}
