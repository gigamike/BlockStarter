<?php

namespace Project\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Project\Model\ProjectEntity;

use Gumlet\ImageResize;
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

  public function getSupplierMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('SupplierMapper');
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
    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $config = $this->getServiceLocator()->get('Config');

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
          $abi = $config['ethereum']['project']['abi'];
          $bytecode = $config['ethereum']['project']['bytecode'];
          $fromAccount = $user->getPublicAddress();
          $toAccount = $config['ethereum']['public_address'];

          $utils = new Utils();
          $ethToWei = $utils->toWei($minimum_contribution, 'ether');
          $minimumContribution = $utils->toHex($ethToWei);

          $contractAddress = null;
          $milestonesCount = 0;

          $web3 = new Web3($config['ethereum']['rpc']);
          $contract = new Contract($web3->provider, $abi);
          $web3->eth->accounts(function ($err, $accounts) use ($contract, $bytecode, $fromAccount, $toAccount, $minimumContribution, &$contractAddress, &$milestonesCount) {
            // print_r($accounts);
            if ($err === null) {
              if (isset($accounts)) {
                $accounts = $accounts;
              } else {
                throw new RuntimeException('Please ensure you have access to web3 json rpc provider.');
              }
            }
            $contract->bytecode($bytecode)->new($minimumContribution, $fromAccount, [
                'from' => $fromAccount,
                'gas' => '0x200b20'
              ], function ($err, $result) use ($contract, $fromAccount, $toAccount, &$contractAddress, &$milestonesCount) {
                // print_r($result);
                if ($err !== null) {
                  throw $err;
                }
                if ($result) {
                  // echo "\nTransaction has made:) id: " . $result . "\n";
                }
                $transactionId = $result;
                $contract->eth->getTransactionReceipt($transactionId, function ($err, $transaction) use ($contract, $fromAccount, $toAccount, &$contractAddress, &$milestonesCount) {
                  if ($err !== null) {
                    throw $err;
                  }
                  if ($transaction) {
                    $contractAddress = $transaction->contractAddress;
                    // echo "\nContract Address: " . $contractAddress;
                    // echo "\nTransaction has mind:) block number: " . $transaction->blockNumber . "\n";

                    $description = "Project Started.";
                    $comments = "No Comments";
                    $recipient = $fromAccount;
                    $value = 0;
                    $time = time();

                    $contract->at($contractAddress)->send('setMilestone', 'general', $description, $comments, $recipient, $value, $time, [
                      'from' => $fromAccount,
                      'gas' => '0x200b20'
                    ], function($error, $result) use ($contract, $contractAddress, $fromAccount, &$milestonesCount){
                      // print_r($error);
                      // print_r($result);
                      if ($error !== null) {
                        throw $error;
                      }
                      if ($result) {
                        // echo "\nTransaction has made:) id: " . $result . "\n";
                      }

                      $contract->at($contractAddress)->call('getMilestonesCount', ['from' => $fromAccount], function($error, $result) use (&$milestonesCount){
                        // print_r($error);
                        // print_r($result);
                        if ($error !== null) {
                          throw $error;
                        }
                        $milestonesCount = $result[0]->value;
                        // echo $milestonesCount;
                      });
                    });

                  }
                });
            });
          });

          $authService = $this->serviceLocator->get('auth_service');

          $project = new ProjectEntity;
          $project->setName($name);
          $project->setDescription($description);
          $project->setContractAddress($contractAddress);
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
          $destination2 = $directory . "/photo_crop_480x320." . $ext;
          if(file_exists($destination2)){
             unlink($destination2);
          }
          $image = new ImageResize($destination);
          $image->crop(480, 320);
          $image->save($destination2);
          if(file_exists($destination)){
             unlink($destination);
          }

          $destination3 = $directory . "/photo_480x320." . $ext;
          if(file_exists($destination3)){
             unlink($destination3);
          }
          $image = new ImageResize($destination2);
          $image->resize(480, 320, $allow_enlarge = True);
          $image->save($destination3);
          if(file_exists($destination2)){
             unlink($destination2);
          }

          $this->flashMessenger()->setNamespace('success')->addMessage('Project added successfully.');
          return $this->redirect()->toRoute('home');
        }
      }
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }

  public function wei2eth($wei)
  {
    return bcdiv($wei,'1000000000000000000',18);
  }

  public function viewAction()
  {

    $contract_address = $this->params('id');
    if (!$contract_address) {
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProjectByContractAddress($contract_address);
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }
    $projectOwner = $this->getUserMapper()->getUser($project->getCreatedUserId());
    if(!$projectOwner){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project Owner.');
      return $this->redirect()->toRoute('home');
    }

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

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

    $projectBalance = 0;
    $web3->eth->getBalance($projectOwner->getPublicAddress(), function ($err, $balance) use ($utils, &$projectBalance){
      // print_r($balance);
    	if ($err !== null) {
    		echo 'Error: ' . $err->getMessage();
    		return;
    	}

      // list($bnq, $bnr) = Utils::toEther($balance->value, 'wei');
      // $projectBalance = (float)$bnq->toString();

      $wei = $balance->value;
      $projectBalance = $this->wei2eth($wei);
    });

    if($_POST){
      $action = isset($_POST['action']) ? $_POST['action'] : null;
      switch($action){
        case 'invest':
          $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
          if(!empty($amount) && is_numeric($amount)){
            $wei = Utils::toWei($amount, 'ether');
            $weiHex = Utils::toHex($wei, 'wei');
            $fromAccount = $user->getPublicAddress();
            $toAccount = $projectOwner->getPublicAddress();

            $eth = $web3->eth;
            $eth->sendTransaction([
                'from' => $fromAccount,
                'to' => $toAccount,
                'value' => $weiHex
            ], function ($err, $transaction) use ($eth, $fromAccount, $toAccount) {
                if ($err !== null) {
                    echo 'Error: ' . $err->getMessage();
                    return;
                }
                // echo 'Tx hash: ' . $transaction . PHP_EOL;
                // get balance
                $eth->getBalance($fromAccount, function ($err, $balance) use($fromAccount) {
                    if ($err !== null) {
                        echo 'Error: ' . $err->getMessage();
                        return;
                    }
                      // echo $fromAccount . ' Balance: ' . $balance . PHP_EOL;
                });
                $eth->getBalance($toAccount, function ($err, $balance) use($toAccount) {
                    if ($err !== null) {
                        echo 'Error: ' . $err->getMessage();
                        return;
                    }
                    // echo $toAccount . ' Balance: ' . $balance . PHP_EOL;
                });
            });

            $contract->at($project->getContractAddress())->send('contribute', [
              'gas' => '0x200b20',
              'gasPrice' => '0x200b20',
              'from' => $fromAccount,
              'to' => $toAccount,
              'value' => $weiHex
            ], function($error, $result) use ($project){
              // print_r($error);
              // print_r($result);
              if ($error !== null) {
                throw $error;
              }
              if ($result) {
                // echo "\nTransaction has made:) id: " . $result . "\n";
                $this->flashMessenger()->setNamespace('success')->addMessage("Thankyou for backing up this project. Transaction successful. Transaction ID: " . $result);
                return $this->redirect()->toRoute('project', array('action' => 'view', 'id' => $project->getContractAddress(),));
                exit();
              }
            });
          }
          break;
        case 'milestone':
          $milestone_type = isset($_POST['milestone_type']) ? $_POST['milestone_type'] : null;
          $supplier_id = isset($_POST['supplier_id']) ? $_POST['supplier_id'] : null;
          $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
          $date_expected = isset($_POST['date_expected']) ? $_POST['date_expected'] : null;
          $description = isset($_POST['description']) ? $_POST['description'] : null;
          $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
          $fromAccount = $user->getPublicAddress();

          switch($milestone_type){
            case 'general':
              $recipient = $fromAccount;
              $value = 0;
              $time = time();
              $contractAddress = $project->getContractAddress();

              $contract->at($contractAddress)->send('setMilestone', 'general', $description, $comments, $recipient, $value, $time, [
                'from' => $fromAccount,
                'gas' => '0x200b20'
              ], function($error, $result) use ($project){
                // print_r($error);
                // print_r($result);
                if ($error !== null) {
                  throw $error;
                }
                if ($result) {
                  // echo "\nTransaction has made:) id: " . $result . "\n";
                  $this->flashMessenger()->setNamespace('success')->addMessage("Milestone added. Transaction successful. Transaction ID: " . $result);
                  header("Location: /project/view/" . $project->getContractAddress());
                  exit();
                }
              });

              break;
            case 'purchase_order':
              $supplier = $this->getSupplierMapper()->getSupplier($supplier_id);
              if(!$supplier){
                $this->flashMessenger()->setNamespace('error')->addMessage('Invalid supplier.');
                header("Location: /project/view/" . $project->getContractAddress());
                exit();
              }

              $value = $amount;
              $description1 = "Payment for " . $supplier->getName() . ". Amount paid ETH " . $value;
              $description = $description1 . "." . $description;

              $recipient = $fromAccount;
              $time = time();
              $contractAddress = $project->getContractAddress();

              $contract->at($contractAddress)->send('setMilestone', 'purchase_order', $description, $comments, $recipient, $value, $time, [
                'from' => $fromAccount,
                'gas' => '0x200b20'
              ], function($error, $result) use ($project){
                // print_r($error);
                // print_r($result);
                if ($error !== null) {
                  throw $error;
                }
                if ($result) {
                  // echo "\nTransaction has made:) id: " . $result . "\n";
                  $this->flashMessenger()->setNamespace('success')->addMessage("Milestone added. Transaction successful. Transaction ID: " . $result);
                  header("Location: /project/view/" . $project->getContractAddress());
                  exit();
                }
              });
              break;
            default:
          }

          exit();
          break;
        default:
      }
    }

    // get milestones
    // count
    $fromAccount = $user->getPublicAddress();

    $milestonesCount = 0;
    $contract->at($project->getContractAddress())->call('getMilestonesCount', ['from' => $fromAccount], function($error, $result) use (&$milestonesCount){
      // print_r($error);
      /// print_r($result);
      if ($error !== null) {
        throw $error;
      }
      $milestonesCount = $result[0]->value;
    });

    $milestones = array();
    if($milestonesCount > 0){
      for($ctr = 0; $ctr < $milestonesCount; $ctr++){
        $contract->at($project->getContractAddress())->call('readMilestone', $ctr, ['from' => $fromAccount], function($error, $result) use (&$milestones){
          // print_r($error);
          // print_r($result);
          if ($error !== null) {
            throw $error;
          }

          $milestones[] = array(
            'milestone_type' => $result[1],
            'description' => $result[2],
            'comments' => $result[3],
            'recipient' => $result[4],
            'value' => $result[5],
            'time' => $result[6]->value,
          );
        });
      }
    }

    $filter = array();
    $order = array('name');
    $suppliers = $this->getSupplierMapper()->fetch(false, $filter, $order);

    return new ViewModel([
      'project' => $project,
      'milestones' => $milestones,
      'user' => $user,
      'projectOwner' => $projectOwner,
      'userBalance' => $userBalance,
      'projectBalance' => $projectBalance,
      'suppliers' => $suppliers,
      'userBalance' => $userBalance,
    ]);
  }
}
