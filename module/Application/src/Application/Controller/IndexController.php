<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function getProjectMapper()
    {
      $sm = $this->getServiceLocator();
      return $sm->get('ProjectMapper');
    }

    public function getUserMapper()
    {
      $sm = $this->getServiceLocator();
      return $sm->get('UserMapper');
    }

    public function indexAction()
    {
      $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
      $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
      $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

      $searchFilter = array();
      if (!empty($search_by)) {
        $searchFilter = (array) json_decode($search_by);
      }
      $order = array('created_datetime DESC');

      $paginator = $this->getProjectMapper()->fetch(true, $searchFilter, $order);
      $paginator->setCurrentPageNumber($page);
      $paginator->setItemCountPerPage(6);

      if ($user = $this->identity()) {
        $user = $this->getUserMapper()->getUser($this->identity()->id);
        if(!$user){
          $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
          return $this->redirect()->toRoute('login');
        }
      }

      $config = $this->getServiceLocator()->get('Config');

      return new ViewModel(array(
        'paginator' => $paginator,
        'search_by' => $search_by,
        'page' => $page,
        'searchFilter' => $searchFilter,
        'route' => $route,
        'user' => $user,
        'config' => $config,
      ));
    }
}
