<?php

namespace Supplier\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Supplier\Model\SupplierEntity;

use Gumlet\ImageResize;

class IndexController extends AbstractActionController
{
  public function getSupplierMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('SupplierMapper');
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
    $order = array('name');

    $paginator = $this->getSupplierMapper()->fetch(true, $searchFilter, $order);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(6);

    return new ViewModel(array(
      'paginator' => $paginator,
      'search_by' => $search_by,
      'page' => $page,
      'searchFilter' => $searchFilter,
      'route' => $route,
    ));
  }
}
