<?php
namespace Project;

use Project\Model\ProjectMapper;
use Project\Model\ProjectContributorMapper;
use Project\Model\ProjectSpendingRequestMapper;
use Project\Model\ProjectSpendingRequestVoteMapper;
use Project\Form\ProjectForm;
use Project\Form\ProjectSpendingRequestForm;

use Supplier\Model\SupplierMapper;

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
            'ProjectMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new ProjectMapper($dbAdapter);
              return $mapper;
            },
            'ProjectContributorMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new ProjectContributorMapper($dbAdapter);
              return $mapper;
            },
            'ProjectSpendingRequestMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new ProjectSpendingRequestMapper($dbAdapter);
              return $mapper;
            },
            'ProjectSpendingRequestVoteMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new ProjectSpendingRequestVoteMapper($dbAdapter);
              return $mapper;
            },
            'ProjectForm' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $form = new ProjectForm($dbAdapter);
              return $form;
            },
            'ProjectSpendingRequestForm' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

              $supplierMapper = new SupplierMapper($dbAdapter);

              $form = new ProjectSpendingRequestForm($dbAdapter, $supplierMapper);
              return $form;
            },
          ),
        );
    }

    public function getViewHelperConfig() {
      return array(
        'factories' => array(
          'getProjectAttributes' => function($sm){
            return new \Project\View\Helper\GetProjectAttributes($sm->getServiceLocator());
          },
        )
      );
    }
}
