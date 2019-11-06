<?php

namespace Project\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Project\Model\ProjectEntity;

use Gumlet\ImageResize;
use Web3\Web3;
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

  public function createAction()
  {
    $config = $this->getServiceLocator()->get('Config');
    $web3 = new Web3($config['ethereumRpcServer']);
    $eth = $web3->eth;

    /*
    $utils = new Utils;
    echo $utils->toHex(0x100000E);
    exit();
    */

    /*
    echo 'Eth Get Account and Balance' . PHP_EOL;
    $eth->accounts(function ($err, $accounts) use ($eth) {
    if ($err !== null) {
      echo 'Error: ' . $err->getMessage();
      return;
    }
    foreach ($accounts as $account) {
        echo 'Account: ' . $account . PHP_EOL;
        $eth->getBalance($account, function ($err, $balance) {
          if ($err !== null) {
            echo 'Error: ' . $err->getMessage();
            return;
          }
          echo 'Balance: ' . $balance . PHP_EOL;
        });
      }
    });
    */

    /*
    $personal = $web3->personal;
    $newAccount = '';
    echo 'Personal Create Account and Unlock Account' . PHP_EOL;
    // create account
    $personal->newAccount('123456', function ($err, $account) use (&$newAccount) {
    	if ($err !== null) {
    	    echo 'Error: ' . $err->getMessage();
    		return;
    	}
    	$newAccount = $account;
    	echo 'New account: ' . $account . PHP_EOL;
    });

    $personal->unlockAccount($newAccount, '123456', function ($err, $unlocked) {
    	if ($err !== null) {
    		echo 'Error: ' . $err->getMessage();
    		return;
    	}
    	if ($unlocked) {
            echo 'New account is unlocked!' . PHP_EOL;
    	} else {
    	    echo 'New account isn\'t unlocked' . PHP_EOL;
    	}
    });

    // get balance
    $web3->eth->getBalance($newAccount, function ($err, $balance) {
    	if ($err !== null) {
    		echo 'Error: ' . $err->getMessage();
    		return;
    	}
    	echo 'Balance: ' . $balance->toString() . PHP_EOL;
    });

    // remember to lock account after transaction
    $personal->lockAccount($newAccount, function ($err, $locked) {
    	if ($err !== null) {
    		echo 'Error: ' . $err->getMessage();
    		return;
    	}
    	if ($locked) {
            echo 'New account is locked!' . PHP_EOL;
    	} else {
    	    echo 'New account isn\'t locked' . PHP_EOL;
    	}
    });
    */
/*
    $eth = $web3->eth;
    echo 'Eth Send Transaction' . PHP_EOL;
    $eth->accounts(function ($err, $accounts) use ($eth) {
        if ($err !== null) {
            echo 'Error: ' . $err->getMessage();
            return;
        }
        $fromAccount = '0xD61dce88a4a479A501c438cC53b70182169f85c3'; // $accounts[0];
        $toAccount = '0x1fBB5dd5492e52D3f9d90de60a327F8C1319FB72'; // $accounts[1];
        // get balance
        $eth->getBalance($fromAccount, function ($err, $balance) use($fromAccount) {
            if ($err !== null) {
                echo 'Error: ' . $err->getMessage();
                return;
            }
            echo $fromAccount . ' Balance: ' . $balance . PHP_EOL;
        });
        $eth->getBalance($toAccount, function ($err, $balance) use($toAccount) {
            if ($err !== null) {
                echo 'Error: ' . $err->getMessage();
                return;
            }
            echo $toAccount . ' Balance: ' . $balance . PHP_EOL;
        });
        // send transaction
        $eth->sendTransaction([
            'from' => $fromAccount,
            'to' => $toAccount,
            'value' => '0x1000000000000000' // https://etherconverter.online/
        ], function ($err, $transaction) use ($eth, $fromAccount, $toAccount) {
            if ($err !== null) {
                echo 'Error: ' . $err->getMessage();
                return;
            }
            echo 'Tx hash: ' . $transaction . PHP_EOL;
            // get balance
            $eth->getBalance($fromAccount, function ($err, $balance) use($fromAccount) {
                if ($err !== null) {
                    echo 'Error: ' . $err->getMessage();
                    return;
                }
                echo $fromAccount . ' Balance: ' . $balance . PHP_EOL;
            });
            $eth->getBalance($toAccount, function ($err, $balance) use($toAccount) {
                if ($err !== null) {
                    echo 'Error: ' . $err->getMessage();
                    return;
                }
                echo $toAccount . ' Balance: ' . $balance . PHP_EOL;
            });
        });
    });

    exit();
*/
    $form = $this->getServiceLocator()->get('ProjectForm');
    $project = new ProjectEntity();
    $form->bind($project);
    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);
      if($form->isValid()) {
        $isError = false;

        $name = $this->getRequest()->getPost('name');
        $description = $this->getRequest()->getPost('description');
        $minimum_contribution = $this->getRequest()->getPost('minimum_contribution');

        if(!isset($_FILES['photo'])){
          $isError = true;
	        $form->get('photo')->setMessages(array('Required field photo.'));
		    }else{
	        $allowed =  array('jpg');
	        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
	        if(!in_array($ext, $allowed) ) {
            $isError = true;
            $form->get('photo')->setMessages(array("File type not allowed. Only " . implode(',', $allowed)));
	        }
	        switch ($_FILES['photo']['error']){
            case 1:
              $isError = true;
              $form->get('photo')->setMessages(array('The file is bigger than this PHP installation allows.'));
              break;
            case 2:
              $isError = true;
              $form->get('photo')->setMessages(array('The file is bigger than this form allows.'));
              break;
            case 3:
              $isError = true;
              $form->get('photo')->setMessages(array('Only part of the file was uploaded.'));
              break;
            case 4:
              $isError = true;
              $form->get('photo')->setMessages(array('No file was uploaded.'));
              break;
            default:
	        }
		    }

        if(!$isError){
          $authService = $this->serviceLocator->get('auth_service');

          $project = new ProjectEntity;
          $project->setName($name);
          $project->setDescription($description);
          $project->setMinimumContribution($minimum_contribution);
          $project->setCreatedUserId($authService->getIdentity()->id);
          $this->getProjectMapper()->save($project);

          $directory = $config['pathProjectPhoto']['absolutePath'] . $project->getId();
          if(!file_exists($directory)){
            mkdir($directory, 0755);
          }

          $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
          $destination = $directory . "/photo_orig." . $ext;
          if(!file_exists($destination)){
             move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
          }
          $destination2 = $directory . "/photo_crop_750x450." . $ext;
          if(file_exists($destination2)){
             unlink($destination2);
          }
          $image = new ImageResize($destination);
          $image->crop(750, 450);
          $image->save($destination2);
          if(file_exists($destination)){
             unlink($destination);
          }

          $destination3 = $directory . "/photo_750x450." . $ext;
          if(file_exists($destination3)){
             unlink($destination3);
          }
          $image = new ImageResize($destination2);
          $image->resize(750, 450, $allow_enlarge = True);
          $image->save($destination3);
          if(file_exists($destination2)){
             unlink($destination2);
          }

          $this->flashMessenger()->setNamespace('success')->addMessage('Project added successfully.');
          return $this->redirect()->toRoute('project');
        }
      }
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }

  public function viewAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($id);
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    if($_POST){
      $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
      if(!empty($amount) && is_numeric($amount)){
        $projectContributor = new ProjectContributorEntity();
        $projectContributor->setProjectId($project->getId());
        $projectContributor->setEosPublicAddress('EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn');
        $projectContributor->setAmount($amount);
        $this->getProjectContributorMapper()->save($projectContributor);

        $command = 'sudo cleos push action peos3 contribute \'["peos"]\' -p bob@active';
        exec($command, $output);

        $this->flashMessenger()->setNamespace('success')->addMessage('Thankyou for backing up this project. You can now vote!');
        return $this->redirect()->toRoute('project', array('action' => 'view', 'id' => $project->getId(),));
      }
    }

    $filter = array(
      'project_id' => $project->getId(),
      'is_finalized' => 'N',
    );
    $countProjectSpendingRequest = $this->getProjectSpendingRequestMapper()->getCountProjectSpendingRequest($filter);
    $countProjectSpendingRequest = is_null($countProjectSpendingRequest['count_id']) ? 0 : $countProjectSpendingRequest['count_id'];

    $filter = array(
      'project_id' => $project->getId(),
    );
    $countProjectContributor = $this->getProjectContributorMapper()->getCountProjectContributor($filter);
    $countProjectContributor = is_null($countProjectContributor['count_id']) ? 0 : $countProjectContributor['count_id'];

    $sumProjectContributorAmount = $this->getProjectContributorMapper()->getSumProjectContributorAmount($filter);
    $sumProjectContributorAmount = is_null($sumProjectContributorAmount['sum_amount']) ? 0 : $sumProjectContributorAmount['sum_amount'];

    return new ViewModel([
      'project' => $project,
      'countProjectSpendingRequest' => $countProjectSpendingRequest,
      'countProjectContributor' => $countProjectContributor,
      'sumProjectContributorAmount' => $sumProjectContributorAmount,
    ]);
  }

  public function requestAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($id);
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    $filter = array(
      'project_id' => $project->getId(),
    );
    $order=array();
    $projectSpendingRequests = $this->getProjectSpendingRequestMapper()->getProjectSpendingRequests(false, $filter, $order);

    return new ViewModel([
      'project' => $project,
      'projectSpendingRequests' => $projectSpendingRequests,
    ]);
  }

  public function addRequestAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($id);
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    $config = $this->getServiceLocator()->get('Config');

    $form = $this->getServiceLocator()->get('ProjectSpendingRequestForm');
    $user = new ProjectEntity();
    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);
      if($form->isValid()) {
        $isError = false;

        $data = $form->getData();

        $description = $data['description'];
        $amount = $data['amount'];
        $supplier_id = $data['supplier_id'];

        $projectSpendingRequest = new ProjectSpendingRequestEntity;
        $projectSpendingRequest->setProjectId($project->getId());
        $projectSpendingRequest->setDescription($data['description']);
        $projectSpendingRequest->setAmount($data['amount']);
        $projectSpendingRequest->setSupplierId($data['supplier_id']);
        $projectSpendingRequest->setIsFinalized('N');
        $this->getProjectSpendingRequestMapper()->save($projectSpendingRequest);

        $command = 'sudo cleos push action peos3 request \'["peos"]\' -p bob@active';
        exec($command, $output);

        $this->flashMessenger()->setNamespace('success')->addMessage('Spending request added successfully.');
        return $this->redirect()->toRoute('project', array('action' => 'request', 'id' => $project->getId(),));
      }
    }

    return new ViewModel([
      'project' => $project,
      'form' => $form,
    ]);
  }
}
