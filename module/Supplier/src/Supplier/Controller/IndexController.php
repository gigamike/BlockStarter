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
    $config = $this->getServiceLocator()->get('Config');

    $form = $this->getServiceLocator()->get('SupplierForm');
    $user = new SupplierEntity();
    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);
      if($form->isValid()) {
        $isError = false;

        $data = $form->getData();

        $name = $data['name'];
        $description = $data['description'];

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

        if(!$isError){
          $supplier = new SupplierEntity;
          $supplier->setName($data['name']);
          $supplier->setDescription($data['description']);
          $this->getSupplierMapper()->save($supplier);

          $directory = $config['pathSupplierPhoto']['absolutePath'] . $supplier->getId();
            if(!file_exists($directory)){
              mkdir($directory, 0755);
            }

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

            $command = 'sudo cleos push action peos3 supplier \'["peos"]\' -p bob@active';
            exec($command, $output);

            $this->flashMessenger()->setNamespace('success')->addMessage('Thank you. Your application  is under review.');
            return $this->redirect()->toRoute('supplier');
        }
      }
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }
}
