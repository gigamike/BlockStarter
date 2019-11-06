<?php

namespace Invest\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
  public function getUserMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('UserMapper');
  }

  public function getProjectMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('ProjectMapper');
  }

  public function indexAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $page = $this->params()->fromRoute('page');
    $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $searchFilter = array();
    if (!empty($search_by)) {
      $searchFilter = (array) json_decode($search_by);
    }
    $searchFilter['created_user_id_not'] = $this->identity()->id;
    $order = array('created_datetime DESC');

    $paginator = $this->getProjectMapper()->fetch(true, $searchFilter, $order);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(6);

    return new ViewModel(array(
      'user' => $user,
      'paginator' => $paginator,
      'search_by' => $search_by,
      'page' => $page,
      'searchFilter' => $searchFilter,
      'route' => $route,
      'action' => $action,
    ));
  }
}
