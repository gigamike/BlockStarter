<?php

namespace Affiliate\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Web3\Web3;
use Web3\Contract;
use Web3\Utils;

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
    $searchFilter['created_user_id'] = $this->identity()->id;
    $order = array('created_datetime DESC');

    $paginator = $this->getProjectMapper()->fetch(true, $searchFilter, $order);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(6);

    $config = $this->getServiceLocator()->get('Config');
    $abi = $config['ethereum']['project']['abi'];
    $web3 = new Web3($config['ethereum']['rpc']);
    $contract = new Contract($web3->provider, $abi);
    $utils = new Utils();

    $personal = $web3->personal;

    $userBalance = 0;
    $web3->eth->getBalance($user->getPublicAddress(), function ($err, $balance) use ($utils, &$userBalance){
      // print_r($balance);
    	if ($err !== null) {
    		echo 'Error: ' . $err->getMessage();
    		return;
    	}

      // $etherInHex = $utils->toEther($balance->toString(), 'wei');
      // $userBalance = $etherInHex[0]->value;

      $wei = $balance->value;
      $userBalance = $this->wei2eth($wei);
    });

    return new ViewModel(array(
      'user' => $user,
      'paginator' => $paginator,
      'search_by' => $search_by,
      'page' => $page,
      'searchFilter' => $searchFilter,
      'route' => $route,
      'action' => $action,
      'config' => $config,
      'userBalance' => $userBalance,
    ));
  }

  public function wei2eth($wei)
  {
    return bcdiv($wei,'1000000000000000000',18);
  }
}
