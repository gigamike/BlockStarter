<?php

namespace User\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Uri\Uri;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;

use User\Form\LoginForm;

class AuthController extends AbstractActionController
{
  public function loginAction()
  {
    $authService = $this->serviceLocator->get('auth_service');
    if ($authService->getIdentity()!=null) {
      if($authService->getIdentity()->role == 'admin'){
        return $this->redirect()->toRoute('admin');
      }else if($authService->getIdentity()->role == 'member'){
        return $this->redirect()->toRoute('home');
      }else if($authService->getIdentity()->role == 'supplier'){
        return $this->redirect()->toRoute('supplier');
      }else{
        return $this->redirect()->toRoute('home');
      }
    }

    $config = $this->getServiceLocator()->get('Config');

    $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
    if (strlen($redirectUrl)>2048) {
      throw new \Exception("Too long redirectUrl argument passed");
    }

    $form = new LoginForm();
    $form->get('redirect_url')->setValue($redirectUrl);

    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);

      if($form->isValid()) {
        $data = $form->getData();

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $authAdapter = new AuthAdapter(
          $dbAdapter,
          'user',
          'email',
          'password',
          "MD5(CONCAT('" . $config['staticSalt'] . "', ?, salt)) AND active='Y'"
        );
        $authAdapter->setIdentity($data['email'])->setCredential($data['password']);
        $result = $authService->authenticate($authAdapter);
        if($result->isValid()) {
          // echo $result->getIdentity() . "\n\n";
          // print_r($authAdapter->getResultRowObject());
          $columnsToOmit = ['password', 'salt'];
          $write = $authAdapter->getResultRowObject(null, $columnsToOmit);
          $authService->getStorage()->write($write);

          if($data['remember_me']){
            $sessionManager = new SessionManager;
            $sessionManager->rememberMe(60*60*24*30);
          }

          $redirectUrl = $this->params()->fromPost('redirect_url', '');
          if (!empty($redirectUrl)) {
            $uri = new Uri($redirectUrl);
            if (!$uri->isValid() || $uri->getHost()!=null)
              throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);

            if(empty($redirectUrl)) {
              return $this->redirect()->toRoute('home');
            } else {
              $this->redirect()->toUrl($redirectUrl);
            }
          }

          if ($authService->getIdentity()!=null) {
            if($authService->getIdentity()->role == 'admin'){
              return $this->redirect()->toRoute('admin');
            }else if($authService->getIdentity()->role == 'member'){
              return $this->redirect()->toRoute('home');
            }else if($authService->getIdentity()->role == 'supplier'){
              return $this->redirect()->toRoute('supplier');
            }else{
              return $this->redirect()->toRoute('home');
            }
          }
        }else{
          $this->flashMessenger()->setNamespace('error')->addMessage('Incorrect login and/or password.');
          return $this->redirect()->toRoute('home');
        }
      }
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }

  public function logoutAction()
  {
    $authService = $this->serviceLocator->get('auth_service');
		if (! $authService->hasIdentity()) {
			return $this->redirect()->toRoute('home');
		}

    $authService->clearIdentity();
    return $this->redirect()->toRoute('home');
  }
}
