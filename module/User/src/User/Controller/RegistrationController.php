<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message as Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Sendmail as Sendmail;

use Gumlet\ImageResize;

use Web3\Web3;
use Web3\Contract;
use Web3\Utils;

use User\Model\UserEntity;
use Supplier\Model\SupplierEntity;

class RegistrationController extends AbstractActionController
{
    public function getUserMapper()
    {
      $sm = $this->getServiceLocator();
      return $sm->get('UserMapper');
    }

    public function getSupplierMapper()
    {
      $sm = $this->getServiceLocator();
      return $sm->get('SupplierMapper');
    }

    public function indexAction()
    {
      $config = $this->getServiceLocator()->get('Config');

      $form = $this->getServiceLocator()->get('RegistrationForm');
      $user = new UserEntity();
		  $form->bind($user);

      $refUser = null;
      if(isset($_COOKIE['REF_ID']) && !empty($_COOKIE['REF_ID'])){
        $refUser = $this->getUserMapper()->getUser($_COOKIE['REF_ID']);
      }

      if($this->getRequest()->isPost()) {
        $data = $this->params()->fromPost();
        $form->setData($data);

        if($form->isValid()) {
          $isError = false;

          $email = $this->getRequest()->getPost('email');
          $role = $this->getRequest()->getPost('role');
          $password = $this->getRequest()->getPost('password');

          switch($role){
            case 'member':
              break;
            case 'supplier':
              $company_name = $this->getRequest()->getPost('company_name');
              $company_description = $this->getRequest()->getPost('company_description');

              if(empty($company_name)){
                $isError = true;
                $form->get('company_name')->setMessages(array('Required field Company Name.'));
              }

              if(empty($company_description)){
                $isError = true;
                $form->get('company_description')->setMessages(array('Required field Company Description.'));
              }

              if(!isset($_FILES['company_photo'])){
                $isError = true;
                $form->get('company_photo')->setMessages(array('Required field Company Photo.'));
              }else{
                $allowed =  array('jpg');
                $ext = pathinfo($_FILES['company_photo']['name'], PATHINFO_EXTENSION);
                if(!in_array($ext, $allowed) ) {
                  $isError = true;
                  $form->get('company_photo')->setMessages(array("File type not allowed. Only " . implode(',', $allowed)));
                }
                switch ($_FILES['company_photo']['error']){
                  case 1:
                    $isError = true;
                    $form->get('company_photo')->setMessages(array('The file is bigger than this PHP installation allows.'));
                    break;
                  case 2:
                    $isError = true;
                    $form->get('company_photo')->setMessages(array('The file is bigger than this form allows.'));
                    break;
                  case 3:
                    $isError = true;
                    $form->get('company_photo')->setMessages(array('Only part of the file was uploaded.'));
                    break;
                  case 4:
                    $isError = true;
                    $form->get('company_photo')->setMessages(array('No file was uploaded.'));
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
                $form->get('bir_certificate')->setMessages(array('Required field Mayor\'s Permit.'));
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
            case 'reseller':
              break;
            default:
          }

          if(!$isError){
            $config = $this->getServiceLocator()->get('Config');
            $abi = $config['ethereum']['project']['abi'];
            $web3 = new Web3($config['ethereum']['rpc']);

            $personal = $web3->personal;
            $newAccount = '';
            $personal->newAccount($email, function ($err, $account) use (&$newAccount) {
            	if ($err !== null) {
            	  echo 'Error: ' . $err->getMessage();
            		return;
            	}
            	$newAccount = $account;
            	// echo 'New account: ' . $account . PHP_EOL;
            });

            $user->setPublicAddress($newAccount);
            $user->setCreatedUserId(0);
            $user->setRole($role);
            $user->setActive('Y');
            $dynamicSalt = $this->getUserMapper()->dynamicSalt();
            $user->setSalt($dynamicSalt);
            $password = md5($config['staticSalt'] . $user->getPassword() . $dynamicSalt);
            $user->setPassword($password);
            $user->setReferrerUserId(0);
            $this->getUserMapper()->save($user);

            switch($role){
              case 'member':
                break;
              case 'supplier':
                $supplier = new SupplierEntity;
                $supplier->setName($company_name);
                $supplier->setPublicAddress($newAccount);
                $supplier->setDescription($company_description);
                $supplier->setCreatedUserId($user->getId());
                $this->getSupplierMapper()->save($supplier);

                $directory = $config['pathSupplier']['absolutePath'] . $supplier->getId();
                if(!file_exists($directory)){
                  mkdir($directory, 0755);
                }

                $ext = pathinfo($_FILES['company_photo']['name'], PATHINFO_EXTENSION);
                $destination = $directory . "/company_photo_orig." . $ext;
                if(!file_exists($destination)){
                   move_uploaded_file($_FILES['company_photo']['tmp_name'], $destination);
                }
                $destination2 = $directory . "/company_photo_crop_480x320." . $ext;
                if(file_exists($destination2)){
                   unlink($destination2);
                }
                $image = new ImageResize($destination);
                $image->crop(480, 320);
                $image->save($destination2);
                if(file_exists($destination)){
                   unlink($destination);
                }

                $destination3 = $directory . "/company_photo_480x320." . $ext;
                if(file_exists($destination3)){
                   unlink($destination3);
                }
                $image = new ImageResize($destination2);
                $image->resize(480, 320, $allow_enlarge = True);
                $image->save($destination3);
                if(file_exists($destination2)){
                   unlink($destination2);
                }

                // mayors permit
                $ext = pathinfo($_FILES['mayors_permit']['name'], PATHINFO_EXTENSION);
                $destination = $directory . "/mayors_permit_orig." . $ext;
                if(!file_exists($destination)){
                   move_uploaded_file($_FILES['mayors_permit']['tmp_name'], $destination);
                }
                $destination2 = $directory . "/mayors_permit_crop_480x320." . $ext;
                if(file_exists($destination2)){
                   unlink($destination2);
                }
                $image = new ImageResize($destination);
                $image->crop(480, 320);
                $image->save($destination2);
                if(file_exists($destination)){
                   unlink($destination);
                }

                $destination3 = $directory . "/mayors_permit_480x320." . $ext;
                if(file_exists($destination3)){
                   unlink($destination3);
                }
                $image = new ImageResize($destination2);
                $image->resize(480, 320, $allow_enlarge = True);
                $image->save($destination3);
                if(file_exists($destination2)){
                   unlink($destination2);
                }

                // bir certificate
                $ext = pathinfo($_FILES['bir_certificate']['name'], PATHINFO_EXTENSION);
                $destination = $directory . "/bir_certificate_orig." . $ext;
                if(!file_exists($destination)){
                   move_uploaded_file($_FILES['bir_certificate']['tmp_name'], $destination);
                }
                $destination2 = $directory . "/bir_certificate_crop_480x320." . $ext;
                if(file_exists($destination2)){
                   unlink($destination2);
                }
                $image = new ImageResize($destination);
                $image->crop(480, 320);
                $image->save($destination2);
                if(file_exists($destination)){
                   unlink($destination);
                }

                $destination3 = $directory . "/bir_certificate_480x320." . $ext;
                if(file_exists($destination3)){
                   unlink($destination3);
                }
                $image = new ImageResize($destination2);
                $image->resize(480, 320, $allow_enlarge = True);
                $image->save($destination3);
                if(file_exists($destination2)){
                   unlink($destination2);
                }

                break;
              case 'reseller':
                break;
              default:
            }

            $subject = 'Thank you for registraion.';
            $message = "Thank you for registraion " . $user->getFirstName() . "!";
            $message .= "\nYour email is: " . $user->getEmail();
            $message .= "\nYour passord is: " . $password;

            /*
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
            */

            /*
            $text = new MimePart($message);
            $text->type = "text/plain";
            $html = new MimePart($message);
            $html->type = "text/html";
            $body = new MimeMessage();
            $body->setParts(array($text, $html));
            $mail = new  Message();
            $mail->setFrom('system@gigamike.net');
            $mail->addTo($user->getEmail());
            $mail->setEncoding("UTF-8");
            $mail->setSubject($subject);
            $mail->setBody($body);
            $transport = new SmtpTransport();
            $options   = new SmtpOptions($config['smtp']);
            $transport->setOptions($options);
            $transport->send($mail);
            */

            $this->flashMessenger()->setNamespace('success')->addMessage('Thank you for registraion.');
            return $this->redirect()->toRoute('login');
          }
        }
      }else{
        if($refUser){
          $form->get('referred_by')->setValue($refUser->getFirstName() . " " . $refUser->getLastName());
        }
      }

      return new ViewModel([
        'form' => $form,
        'config' => $config,

      ]);
    }
}
