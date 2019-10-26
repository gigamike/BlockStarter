<?php

namespace User\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    public function loginAction()
    {
      return $this->redirect()->toRoute('home');
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
