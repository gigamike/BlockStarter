<?php

namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Member\Model\MedicalRecordEntity;
use Gumlet\ImageResize;

class IndexController extends AbstractActionController
{
  public function getIncentiveMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('IncentiveMapper');
  }

  public function getProductMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('ProductMapper');
  }

  public function getUserMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('UserMapper');
  }

  public function getMedicalRecordMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('MedicalRecordMapper');
  }

  public function indexAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $page = $this->params()->fromRoute('page');
    $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

		$filter = array();
		if (!empty($search_by)) {
			$filter = (array) json_decode($search_by);
		}

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
		));
	}

  public function tagCashAction()
  {
    $config = $this->getServiceLocator()->get('Config');

    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $page = $this->params()->fromRoute('page');
    $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('member', array('action'=>'portal'));
    }

    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();

      $wallet = $data['wallet'];
      $amount = $data['amount'];
      $email = $data['email'];
      $pin = $data['pin'];

      header("Content-Type: application/json; charset=UTF-8");
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, "https://apibeta.tagcash.com/oauth/accesstoken");
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);

       $data = array(
          "client_id" => $config['tagcash']['CLIENT ID'],
          "client_secret" => $config['tagcash']['CLIENT SECRET'],
          "grant_type" => "client_credentials"
       );
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

       $contents = curl_exec($ch);
       $obj = json_decode($contents, false);

       $result = $obj->result;
       $accesstoken = $result->access_token;

       if(!$accesstoken){
          $this->flashMessenger()->setNamespace('error')->addMessage('Merchant Access Token Is Not Found');
          return $this->redirect()->toRoute('member', ['action' => 'portal',]);
       }

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, "https://apibeta.tagcash.com/wallet/charge");
       curl_setopt($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_POST, 1);

       $data = array(
          "access_token" => $accesstoken,
          "amount" => $amount,
          "pin" => $pin,
          "from_id" => $email,
          "wallet_id" => $wallet
       );

       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       $contents = curl_exec($ch);
       $contents = json_decode($contents);

       /*
       if(isset($contents->error)){
          $this->flashMessenger()->setNamespace('error')->addMessage('Pay using tagcash failed');
          return $this->redirect()->toRoute('member', ['action' => 'portal',]);
       }

       if(isset($contents->result)){
          $this->flashMessenger()->setNamespace('success')->addMessage('Pay using tagcash done successfully');
          return $this->redirect()->toRoute('member', ['action' => 'video',]);
       }
       */
       $this->flashMessenger()->setNamespace('success')->addMessage('Pay using tagcash done successfully');
       return $this->redirect()->toRoute('member', ['action' => 'video', 'id' => $id, 'status' => 'success']);
    }

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
		));
	}

  public function createAction()
  {
    $config = $this->getServiceLocator()->get('Config');

    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $patient_public_address = $this->params()->fromQuery('public_address');
    if (!$patient_public_address) {
      return $this->redirect()->toRoute('member');
    }
    $patient = $this->getUserMapper()->getUserByPublicAddress($patient_public_address);
    if(!$patient){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid User.');
      return $this->redirect()->toRoute('member');
    }

    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();

      $patient_public_address = $data['patient_public_address'];
      $doctor_public_address = $data['doctor_public_address'];
      $diagnostics = $data['diagnostics'];
      $medical_prescription = $data['medical_prescription'];

      $this->flashMessenger()->setNamespace('success')->addMessage('Transaction successful.');
      return $this->redirect()->toRoute('member');
    }

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
      'patient' => $patient,
		));
	}

  public function profileAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
		));
	}

  public function scanAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
		));
	}

  public function videoAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $status = $this->params('status');

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('member', array('action'=>'doctors'));
    }
    $doctor = $this->getUserMapper()->getUser($id);
    if(!$doctor){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid User.');
      return $this->redirect()->toRoute('member', array('action'=>'doctors'));
    }

    return new ViewModel([
      'user' => $user,
      'doctor' => $doctor,
      'route' => $route,
      'action' => $action,
      'status' => $status,
    ]);
  }

  public function portalAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
    $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

    $searchFilter = array();
    if (!empty($search_by)) {
      $searchFilter = (array) json_decode($search_by);
    }
    if($this->identity()->role == 'doctor'){
      $searchFilter['role'] = 'patient';
    }else{
      $searchFilter['role'] = 'doctor';
    }

    $order = array('first_name' , 'last_name');
    $paginator = $this->getUserMapper()->fetch(true, $searchFilter, $order);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(12);

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
      'paginator' => $paginator,
      'search_by' => $search_by,
      'page' => $page,
      'searchFilter' => $searchFilter,
		));
	}

  public function incentivesAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $authService = $this->serviceLocator->get('auth_service');
    if (!$authService->getIdentity()) {
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $filter = array(
      'created_user_id' => $user->getId(),
    );
    $order=array();
    $incentives = $this->getIncentiveMapper()->getIncentives(false, $filter, $order);

    return new ViewModel([
      'incentives' => $incentives,
      'user' => $user,
      'route' => $route,
      'action' => $action,
    ]);
  }

  public function medicalRecordsAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $page = $this->params()->fromRoute('page');
    $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

    $searchFilter = array();
    if (!empty($search_by)) {
      $searchFilter = (array) json_decode($search_by);
    }

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $order = array('created_datetime DESC');
    $paginator = $this->getMedicalRecordMapper()->fetch(true, $searchFilter, $order);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(12);

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,

      'paginator' => $paginator,
      'search_by' => $search_by,
      'page' => $page,
      'searchFilter' => $searchFilter,
		));
	}

  public function medicalRecordAddAction()
  {
    $config = $this->getServiceLocator()->get('Config');

    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $form = $this->getServiceLocator()->get('MedicalRecordAddForm');
    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);

      if($form->isValid()) {
        $isError = false;

        if(!isset($_FILES['photo'])){
          $isError = true;
          $form->get('photo')->setMessages(array('Required field Company Logo.'));
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
          $medicalRecord = new MedicalRecordEntity;
          $medicalRecord->setCreatedUserId($this->identity()->id);
          $medicalRecord->setName("Upload " . date('Y-m-d H:i:s'));
          $this->getMedicalRecordMapper()->save($medicalRecord);

          $directory = $config['pathMedicalPhoto']['absolutePath'] . $medicalRecord->getId();
          if(!file_exists($directory)){
            mkdir($directory, 0755);
          }

          $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
          $destination = $directory . "/photo-orig." . $ext;
          if(!file_exists($destination)){
             move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
          }
          $destination2 = $directory . "/photo-crop-750x450." . $ext;
          if(file_exists($destination2)){
             unlink($destination2);
          }
          $image = new ImageResize($destination);
          $image->crop(750, 450);
          $image->save($destination2);
          if(file_exists($destination)){
             unlink($destination);
          }

          $destination3 = $directory . "/photo-750x450." . $ext;
          if(file_exists($destination3)){
             unlink($destination3);
          }
          $image = new ImageResize($destination2);
          $image->resize(750, 450, $allow_enlarge = True);
          $image->save($destination3);
          if(file_exists($destination2)){
             unlink($destination2);
          }

          $this->flashMessenger()->setNamespace('success')->addMessage('Medical Record Uploaded.');
          return $this->redirect()->toRoute('member', ['action' => 'medical-record-edit', 'id' => $medicalRecord->getId()]);
        }
      }
    }

    return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
      'form' => $form,
		));
  }

  public function medicalRecordEditAction()
  {
    $config = $this->getServiceLocator()->get('Config');

    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('member', array('action'=>'medical-records'));
    }

    $medicalRecord = $this->getMedicalRecordMapper()->getMedicalRecord($id);
    if(!$medicalRecord){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid User.');
      return $this->redirect()->toRoute('member', array('action'=>'medical-records'));
    }

    $form = $this->getServiceLocator()->get('MedicalRecordEditForm');
    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);

      if($form->isValid()) {
      }
    }else{
      $form->get('name')->setValue($medicalRecord->getName());
      $form->get('tags')->setValue('X-ray,xray,laser');
      $form->get('request_by')->setValue(2);
      $form->get('hospital')->setValue(10);
    }

    return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,
      'config' => $config,
      'medicalRecord' => $medicalRecord,
      'form' => $form,
		));
  }

  public function pharmaceuticalAction()
  {
    $route = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    $action = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

    $page = $this->params()->fromRoute('page');
    $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

    $searchFilter = array();
    if (!empty($search_by)) {
      $searchFilter = (array) json_decode($search_by);
    }

    $user = $this->getUserMapper()->getUser($this->identity()->id);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('You need to login or register first.');
      return $this->redirect()->toRoute('login');
    }

    $order = array('created_datetime DESC');
    $paginator = $this->getProductMapper()->fetch(true, $searchFilter, $order);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(12);

		return new ViewModel(array(
      'route' => $route,
      'action' => $action,
      'user' => $user,

      'paginator' => $paginator,
      'search_by' => $search_by,
      'page' => $page,
      'searchFilter' => $searchFilter,
		));
	}
}
