<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

use User\Model\UserEntity;

use Gumlet\ImageResize;

class RegistrationController extends AbstractActionController
{
    public function getUserMapper()
    {
      $sm = $this->getServiceLocator();
      return $sm->get('UserMapper');
    }

    public function indexAction()
    {
      $config = $this->getServiceLocator()->get('Config');

      $form = $this->getServiceLocator()->get('RegistrationForm');
      $user = new UserEntity();
		  $form->bind($user);

      if($this->getRequest()->isPost()) {
        $data = $this->params()->fromPost();
        $form->setData($data);

        if($form->isValid()) {
          $isError = false;

          $role = $this->getRequest()->getPost('role');
          $company_name = $this->getRequest()->getPost('company_name');
          $company_address = $this->getRequest()->getPost('company_address');

          switch ($role) {
            case 'supplier':
              if(empty($company_name)){
                $isError = true;
                $form->get('company_name')->setMessages(array('Required field Company Name.'));
              }

              if(empty($company_address)){
                $isError = true;
                $form->get('company_address')->setMessages(array('Required field Company Address.'));
              }

              if(!isset($_FILES['company_logo'])){
                $isError = true;
      	        $form->get('company_logo')->setMessages(array('Required field Company Logo.'));
      		    }else{
      	        $allowed =  array('jpg');
      	        $ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);
      	        if(!in_array($ext, $allowed) ) {
                  $isError = true;
                  $form->get('company_logo')->setMessages(array("File type not allowed. Only " . implode(',', $allowed)));
      	        }
      	        switch ($_FILES['company_logo']['error']){
                  case 1:
                    $isError = true;
                    $form->get('company_logo')->setMessages(array('The file is bigger than this PHP installation allows.'));
                    break;
                  case 2:
                    $isError = true;
                    $form->get('company_logo')->setMessages(array('The file is bigger than this form allows.'));
                    break;
                  case 3:
                    $isError = true;
                    $form->get('company_logo')->setMessages(array('Only part of the file was uploaded.'));
                    break;
                  case 4:
                    $isError = true;
                    $form->get('company_logo')->setMessages(array('No file was uploaded.'));
                    break;
                  default:
      	        }
      		    }

              if(!isset($_FILES['mayors_permit'])){
                $isError = true;
      	        $form->get('mayors_permit')->setMessages(array('Required field Mayor\'s Permit.'));
      		    }else{
      	        $allowed =  array('jpg');
      	        $ext = pathinfo($_FILES['mayors_permit']['name'], PATHINFO_EXTENSION);
      	        if(!in_array($ext, $allowed) ) {
                  $isError = true;
                  $form->get('mayors_permit')->setMessages(array("File type not allowed. Only " . implode(',', $allowed)));
      	        }
      	        switch ($_FILES['mayors_permit']['error']){
                  case 1:
                    $isError = true;
                    $form->get('mayors_permit')->setMessages(array('The file is bigger than this PHP installation allows.'));
                    break;
                  case 2:
                    $isError = true;
                    $form->get('mayors_permit')->setMessages(array('The file is bigger than this form allows.'));
                    break;
                  case 3:
                    $isError = true;
                    $form->get('mayors_permit')->setMessages(array('Only part of the file was uploaded.'));
                    break;
                  case 4:
                    $isError = true;
                    $form->get('mayors_permit')->setMessages(array('No file was uploaded.'));
                    break;
                  default:
      	        }
      		    }

              if(!isset($_FILES['bir_certificate'])){
                $isError = true;
      	        $form->get('bir_certificate')->setMessages(array('Required field BIR Certificate.'));
      		    }else{
      	        $allowed =  array('jpg');
      	        $ext = pathinfo($_FILES['bir_certificate']['name'], PATHINFO_EXTENSION);
      	        if(!in_array($ext, $allowed) ) {
                  $isError = true;
                  $form->get('bir_certificate')->setMessages(array("File type not allowed. Only " . implode(',', $allowed)));
      	        }
      	        switch ($_FILES['bir_certificate']['error']){
                  case 1:
                    $isError = true;
                    $form->get('bir_certificate')->setMessages(array('The file is bigger than this PHP installation allows.'));
                    break;
                  case 2:
                    $isError = true;
                    $form->get('bir_certificate')->setMessages(array('The file is bigger than this form allows.'));
                    break;
                  case 3:
                    $isError = true;
                    $form->get('bir_certificate')->setMessages(array('Only part of the file was uploaded.'));
                    break;
                  case 4:
                    $isError = true;
                    $form->get('bir_certificate')->setMessages(array('No file was uploaded.'));
                    break;
                  default:
      	        }
      		    }
              break;
            case 'manager-operation':
              $user->setCompanyName(null);
              $user->setCompanyAddress(null);
              break;
            case 'manager-finance':
              $user->setCompanyName(null);
              $user->setCompanyAddress(null);
              break;
            case 'engineer':
              $user->setCompanyName(null);
              $user->setCompanyAddress(null);
              break;
            default:
          }

					$user->setCreatedUserId(0);
          $user->setRole($role);
          $user->setActive('Y');
					$dynamicSalt = $this->getUserMapper()->dynamicSalt();
					$user->setSalt($dynamicSalt);
					$password = md5($config['staticSalt'] . $user->getPassword() . $dynamicSalt);
					$user->setPassword($password);
          $this->getUserMapper()->save($user);

          switch ($role) {
            case 'supplier':
              $directory = $config['pathSupplierPhoto']['absolutePath'] . $user->getId();
              if(!file_exists($directory)){
                mkdir($directory, 0755);
              }

              $ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);
              $destination = $directory . "/company_logo-orig." . $ext;
              if(!file_exists($destination)){
                 move_uploaded_file($_FILES['company_logo']['tmp_name'], $destination);
              }
              $destination2 = $directory . "/company_logo-480x320." . $ext;
              if(file_exists($destination2)){
                 unlink($destination2);
              }
              $image = new ImageResize($destination);
              $image->resize(480, 320);
              $image->save($destination2);

              $ext = pathinfo($_FILES['mayors_permit']['name'], PATHINFO_EXTENSION);
              $destination = $directory . "/mayors-permit-orig." . $ext;
              if(!file_exists($destination)){
                 move_uploaded_file($_FILES['mayors_permit']['tmp_name'], $destination);
              }
              $destination2 = $directory . "/mayors-permit-480x320." . $ext;
              if(file_exists($destination2)){
                 unlink($destination2);
              }
              $image = new ImageResize($destination);
              $image->resize(480, 320);
              $image->save($destination2);

              $ext = pathinfo($_FILES['bir_certificate']['name'], PATHINFO_EXTENSION);
              $destination = $directory . "/bir-certificate-orig." . $ext;
              if(!file_exists($destination)){
                 move_uploaded_file($_FILES['bir_certificate']['tmp_name'], $destination);
              }
              $destination2 = $directory . "/bir-certificate-480x320." . $ext;
              if(file_exists($destination2)){
                 unlink($destination2);
              }
              $image = new ImageResize($destination);
              $image->resize(480, 320);
              $image->save($destination2);
              break;
            case 'manager-operation':
              break;
            case 'manager-finance':
              break;
            case 'engineer':
              break;
            case 'supplier':
              break;
            default:
          }

          $message = "Thank you for registraion " . $user->getFirstName() . "!";
          $subject = 'Thank you for registraion.';

          try {
            $mail = new Message();
            $mail->setFrom($config['email']);
            $mail->addTo($user->getEmail());
            $mail->setSubject($subject);
            $mail->setBody($message);

            // Send E-mail message
            $transport = new Sendmail('-f'. $config['email']);
            // $transport->send($mail);
          } catch(\Exception $e) {
          }

          $this->flashMessenger()->setNamespace('success')->addMessage('Thank you for registraion.');
          return $this->redirect()->toRoute('home');
        }
      }

      return new ViewModel([
        'form' => $form,
        'config' => $config,
      ]);
    }
}
