<?php
namespace Member;

use Member\Model\MedicalRecordMapper;
use Member\Form\MedicalRecordAddForm;
use Member\Form\MedicalRecordEditForm;

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
          'MedicalRecordMapper' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $mapper = new MedicalRecordMapper($dbAdapter);
            return $mapper;
          },
          'MedicalRecordAddForm' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

            $form = new MedicalRecordAddForm($dbAdapter);
            return $form;
          },
          'MedicalRecordEditForm' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

            $form = new MedicalRecordEditForm($dbAdapter);
            return $form;
          },
  			),
    	);
    }
}
