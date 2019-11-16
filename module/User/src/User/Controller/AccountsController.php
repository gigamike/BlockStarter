<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Web3\Web3;
use Web3\Contract;
use Web3\Utils;

class AccountsController extends AbstractActionController
{
  private function _getResponseWithHeader()
  {
    $response = $this->getResponse();
    $response->getHeaders()
             // make can accessed by *
             ->addHeaderLine('Access-Control-Allow-Origin','*')
             // set allow methods
             ->addHeaderLine('Access-Control-Allow-Methods','POST PUT DELETE GET')
						 // json
						 ->addHeaderLine('Content-Type', 'application/json');
    return $response;
  }

  public function indexAction()
  {
    $results = array();

    $config = $this->getServiceLocator()->get('Config');
    $abi = $config['ethereum']['project']['abi'];
    $web3 = new Web3($config['ethereum']['rpc']);

    $eth = $web3->eth;
    echo 'Eth Get Account and Balance' . "<br>" . PHP_EOL;
    $eth->accounts(function ($err, $accounts) use ($eth) {
        if ($err !== null) {
            echo 'Error: ' . $err->getMessage();
            return;
        }
        foreach ($accounts as $account) {
            echo 'Account: ' . $account . "<br>" . PHP_EOL;
            $eth->getBalance($account, function ($err, $balance) {
                if ($err !== null) {
                    echo 'Error: ' . $err->getMessage();
                    return;
                }
                echo 'Balance: ' . $balance . "<br><br>" . PHP_EOL;
            });
        }
    });

    exit();
	}
}
