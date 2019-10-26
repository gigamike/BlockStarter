<?php
namespace Supplier;

use Supplier\Model\SupplierMapper;
use Supplier\Form\SupplierForm;

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
            'SupplierMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new SupplierMapper($dbAdapter);
              return $mapper;
            },
            'SupplierForm' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $form = new SupplierForm($dbAdapter);
              return $form;
            },
          ),
        );
    }
}
