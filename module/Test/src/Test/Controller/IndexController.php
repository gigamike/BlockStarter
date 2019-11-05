<?php

namespace Test\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Web3\Web3;
use Web3\Contract;

class IndexController extends AbstractActionController
{
  public function indexAction()
  {
    $rpc = 'http://127.0.0.1:7545';
    $web3 = new Web3($rpc);
    $abi = '[{"constant":false,"inputs":[{"name":"_fName","type":"string"},{"name":"_age","type":"uint256"}],"name":"setInstructor","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getInstructor","outputs":[{"name":"","type":"string"},{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"}]';
    $bytecode = '{
	"linkReferences": {},
	"object": "608060405234801561001057600080fd5b506102fa806100206000396000f30060806040526004361061004c576000357c0100000000000000000000000000000000000000000000000000000000900463ffffffff16806322faf03a146100515780633c1b81a5146100c4575b600080fd5b34801561005d57600080fd5b506100c2600480360381019080803590602001908201803590602001908080601f01602080910402602001604051908101604052809392919081815260200183838082843782019150505050505091929192908035906020019092919050505061015b565b005b3480156100d057600080fd5b506100d961017d565b6040518080602001838152602001828103825284818151815260200191508051906020019080838360005b8381101561011f578082015181840152602081019050610104565b50505050905090810190601f16801561014c5780820380516001836020036101000a031916815260200191505b50935050505060405180910390f35b8160009080519060200190610171929190610229565b50806001819055505050565b6060600080600154818054600181600116156101000203166002900480601f01602080910402602001604051908101604052809291908181526020018280546001816001161561010002031660029004801561021a5780601f106101ef5761010080835404028352916020019161021a565b820191906000526020600020905b8154815290600101906020018083116101fd57829003601f168201915b50505050509150915091509091565b828054600181600116156101000203166002900490600052602060002090601f016020900481019282601f1061026a57805160ff1916838001178555610298565b82800160010185558215610298579182015b8281111561029757825182559160200191906001019061027c565b5b5090506102a591906102a9565b5090565b6102cb91905b808211156102c75760008160009055506001016102af565b5090565b905600a165627a7a72305820d6a55fee72e5a6a70de3bdd4b3bd6a0b32e18a8b730dec2a37f81275dfc0026e0029",
	"opcodes": "PUSH1 0x80 PUSH1 0x40 MSTORE CALLVALUE DUP1 ISZERO PUSH2 0x10 JUMPI PUSH1 0x0 DUP1 REVERT JUMPDEST POP PUSH2 0x2FA DUP1 PUSH2 0x20 PUSH1 0x0 CODECOPY PUSH1 0x0 RETURN STOP PUSH1 0x80 PUSH1 0x40 MSTORE PUSH1 0x4 CALLDATASIZE LT PUSH2 0x4C JUMPI PUSH1 0x0 CALLDATALOAD PUSH29 0x100000000000000000000000000000000000000000000000000000000 SWAP1 DIV PUSH4 0xFFFFFFFF AND DUP1 PUSH4 0x22FAF03A EQ PUSH2 0x51 JUMPI DUP1 PUSH4 0x3C1B81A5 EQ PUSH2 0xC4 JUMPI JUMPDEST PUSH1 0x0 DUP1 REVERT JUMPDEST CALLVALUE DUP1 ISZERO PUSH2 0x5D JUMPI PUSH1 0x0 DUP1 REVERT JUMPDEST POP PUSH2 0xC2 PUSH1 0x4 DUP1 CALLDATASIZE SUB DUP2 ADD SWAP1 DUP1 DUP1 CALLDATALOAD SWAP1 PUSH1 0x20 ADD SWAP1 DUP3 ADD DUP1 CALLDATALOAD SWAP1 PUSH1 0x20 ADD SWAP1 DUP1 DUP1 PUSH1 0x1F ADD PUSH1 0x20 DUP1 SWAP2 DIV MUL PUSH1 0x20 ADD PUSH1 0x40 MLOAD SWAP1 DUP2 ADD PUSH1 0x40 MSTORE DUP1 SWAP4 SWAP3 SWAP2 SWAP1 DUP2 DUP2 MSTORE PUSH1 0x20 ADD DUP4 DUP4 DUP1 DUP3 DUP5 CALLDATACOPY DUP3 ADD SWAP2 POP POP POP POP POP POP SWAP2 SWAP3 SWAP2 SWAP3 SWAP1 DUP1 CALLDATALOAD SWAP1 PUSH1 0x20 ADD SWAP1 SWAP3 SWAP2 SWAP1 POP POP POP PUSH2 0x15B JUMP JUMPDEST STOP JUMPDEST CALLVALUE DUP1 ISZERO PUSH2 0xD0 JUMPI PUSH1 0x0 DUP1 REVERT JUMPDEST POP PUSH2 0xD9 PUSH2 0x17D JUMP JUMPDEST PUSH1 0x40 MLOAD DUP1 DUP1 PUSH1 0x20 ADD DUP4 DUP2 MSTORE PUSH1 0x20 ADD DUP3 DUP2 SUB DUP3 MSTORE DUP5 DUP2 DUP2 MLOAD DUP2 MSTORE PUSH1 0x20 ADD SWAP2 POP DUP1 MLOAD SWAP1 PUSH1 0x20 ADD SWAP1 DUP1 DUP4 DUP4 PUSH1 0x0 JUMPDEST DUP4 DUP2 LT ISZERO PUSH2 0x11F JUMPI DUP1 DUP3 ADD MLOAD DUP2 DUP5 ADD MSTORE PUSH1 0x20 DUP2 ADD SWAP1 POP PUSH2 0x104 JUMP JUMPDEST POP POP POP POP SWAP1 POP SWAP1 DUP2 ADD SWAP1 PUSH1 0x1F AND DUP1 ISZERO PUSH2 0x14C JUMPI DUP1 DUP3 SUB DUP1 MLOAD PUSH1 0x1 DUP4 PUSH1 0x20 SUB PUSH2 0x100 EXP SUB NOT AND DUP2 MSTORE PUSH1 0x20 ADD SWAP2 POP JUMPDEST POP SWAP4 POP POP POP POP PUSH1 0x40 MLOAD DUP1 SWAP2 SUB SWAP1 RETURN JUMPDEST DUP2 PUSH1 0x0 SWAP1 DUP1 MLOAD SWAP1 PUSH1 0x20 ADD SWAP1 PUSH2 0x171 SWAP3 SWAP2 SWAP1 PUSH2 0x229 JUMP JUMPDEST POP DUP1 PUSH1 0x1 DUP2 SWAP1 SSTORE POP POP POP JUMP JUMPDEST PUSH1 0x60 PUSH1 0x0 DUP1 PUSH1 0x1 SLOAD DUP2 DUP1 SLOAD PUSH1 0x1 DUP2 PUSH1 0x1 AND ISZERO PUSH2 0x100 MUL SUB AND PUSH1 0x2 SWAP1 DIV DUP1 PUSH1 0x1F ADD PUSH1 0x20 DUP1 SWAP2 DIV MUL PUSH1 0x20 ADD PUSH1 0x40 MLOAD SWAP1 DUP2 ADD PUSH1 0x40 MSTORE DUP1 SWAP3 SWAP2 SWAP1 DUP2 DUP2 MSTORE PUSH1 0x20 ADD DUP3 DUP1 SLOAD PUSH1 0x1 DUP2 PUSH1 0x1 AND ISZERO PUSH2 0x100 MUL SUB AND PUSH1 0x2 SWAP1 DIV DUP1 ISZERO PUSH2 0x21A JUMPI DUP1 PUSH1 0x1F LT PUSH2 0x1EF JUMPI PUSH2 0x100 DUP1 DUP4 SLOAD DIV MUL DUP4 MSTORE SWAP2 PUSH1 0x20 ADD SWAP2 PUSH2 0x21A JUMP JUMPDEST DUP3 ADD SWAP2 SWAP1 PUSH1 0x0 MSTORE PUSH1 0x20 PUSH1 0x0 KECCAK256 SWAP1 JUMPDEST DUP2 SLOAD DUP2 MSTORE SWAP1 PUSH1 0x1 ADD SWAP1 PUSH1 0x20 ADD DUP1 DUP4 GT PUSH2 0x1FD JUMPI DUP3 SWAP1 SUB PUSH1 0x1F AND DUP3 ADD SWAP2 JUMPDEST POP POP POP POP POP SWAP2 POP SWAP2 POP SWAP2 POP SWAP1 SWAP2 JUMP JUMPDEST DUP3 DUP1 SLOAD PUSH1 0x1 DUP2 PUSH1 0x1 AND ISZERO PUSH2 0x100 MUL SUB AND PUSH1 0x2 SWAP1 DIV SWAP1 PUSH1 0x0 MSTORE PUSH1 0x20 PUSH1 0x0 KECCAK256 SWAP1 PUSH1 0x1F ADD PUSH1 0x20 SWAP1 DIV DUP2 ADD SWAP3 DUP3 PUSH1 0x1F LT PUSH2 0x26A JUMPI DUP1 MLOAD PUSH1 0xFF NOT AND DUP4 DUP1 ADD OR DUP6 SSTORE PUSH2 0x298 JUMP JUMPDEST DUP3 DUP1 ADD PUSH1 0x1 ADD DUP6 SSTORE DUP3 ISZERO PUSH2 0x298 JUMPI SWAP2 DUP3 ADD JUMPDEST DUP3 DUP2 GT ISZERO PUSH2 0x297 JUMPI DUP3 MLOAD DUP3 SSTORE SWAP2 PUSH1 0x20 ADD SWAP2 SWAP1 PUSH1 0x1 ADD SWAP1 PUSH2 0x27C JUMP JUMPDEST JUMPDEST POP SWAP1 POP PUSH2 0x2A5 SWAP2 SWAP1 PUSH2 0x2A9 JUMP JUMPDEST POP SWAP1 JUMP JUMPDEST PUSH2 0x2CB SWAP2 SWAP1 JUMPDEST DUP1 DUP3 GT ISZERO PUSH2 0x2C7 JUMPI PUSH1 0x0 DUP2 PUSH1 0x0 SWAP1 SSTORE POP PUSH1 0x1 ADD PUSH2 0x2AF JUMP JUMPDEST POP SWAP1 JUMP JUMPDEST SWAP1 JUMP STOP LOG1 PUSH6 0x627A7A723058 KECCAK256 0xd6 0xa5 0x5f 0xee PUSH19 0xE5A6A70DE3BDD4B3BD6A0B32E18A8B730DEC2A CALLDATACOPY 0xf8 SLT PUSH22 0xDFC0026E002900000000000000000000000000000000 ",
	"sourceMap": "24:244:0:-;;;;8:9:-1;5:2;;;30:1;27;20:12;5:2;24:244:0;;;;;;;"
}';
    $contractAddress = '0x97c34d672bde50145098b4aa0fe1310590698a26';
    $userAddress = '0xfde49d3002f2bba94182ad274c7ca58c518af1ca';
    $contract = new Contract($web3->provider, $abi);
    $contract->at($contractAddress)->send('setInstructor', 'Mik Galon', '38', ['from' => $userAddress], function($error, $result) use ($contract, $contractAddress, $userAddress){
      print_r($error);
      print_r($result);
      if ($error !== null) {
        throw $error;
      }
      if ($result) {
        echo "\nTransaction has made:) id: " . $result . "\n";
      }

      $contract->at($contractAddress)->call('getInstructor', ['from' => $userAddress], function($error, $result) use ($contract, $contractAddress, $userAddress){
        print_r($error);
        print_r($result);
        if ($error !== null) {
          throw $error;
        }
      });
    });

    return $this->getResponse();
  }
}