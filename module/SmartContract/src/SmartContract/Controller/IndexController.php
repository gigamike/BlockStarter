<?php

namespace SmartContract\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Web3\Web3;
use Web3\Contract;

class IndexController extends AbstractActionController
{
  public function indexAction()
  {
    // remix http://localhost:8545
    // ganache http://127.0.0.1:7545

    $web3 = new Web3('http://127.0.0.1:7545');

    $abi = '[
      {
        "constant": false,
        "inputs": [
          {
            "name": "x",
            "type": "uint256"
          }
        ],
        "name": "set",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "constant": true,
        "inputs": [],
        "name": "get",
        "outputs": [
          {
            "name": "",
            "type": "uint256"
          }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      }
    ]';

    $bytecode = '0x608060405234801561001057600080fd5b5060bd8061001f6000396000f3fe6080604052348015600f57600080fd5b506004361060325760003560e01c806360fe47b11460375780636d4ce63c146062575b600080fd5b606060048036036020811015604b57600080fd5b8101908080359060200190929190505050607e565b005b60686088565b6040518082815260200191505060405180910390f35b8060008190555050565b6000805490509056fea165627a7a72305820a21c88151a843af0fa538d28830ad6f315ce2e67d5f0beb546fa4bc4432ce9460029';
    $params = array(

    );
    $callback = 'SimpleStorage';

    $contract = new Contract('http://127.0.0.1:7545', $abi);
    $contract->at($contractAddress)->call($functionName, $params, $callback);


    $contract->bytecode($bytecode)->new($params, function(){

    });

		return $this->getResponse();
	}
}
