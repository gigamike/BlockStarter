<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

 return array(
   'service_manager' => array(
     'factories' => array(
       'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
       'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
     ),
     'aliases' => array(
       'Zend\Authentication\AuthenticationService' => 'auth_service',
     ),
   ),
   /*
   'session' => array(
    'config' => array(
        'class' => 'Zend\Session\Config\SessionConfig',
        'options' => array(
            'name' => 'myapp',
        ),
    ),
    'storage' => 'Zend\Session\Storage\SessionArrayStorage',
    'validators' => array(
      'Zend\Session\Validator\RemoteAddr',
      'Zend\Session\Validator\HttpUserAgent',
    ),
   ),
   */
   'baseUrl' => 'https://uhack2019.gigamike.net/',
   // for console, http://stackoverflow.com/questions/2412009/starting-with-zend-tutorial-zend-db-adapter-throws-exception-sqlstatehy000
   // sudo mkdir /var/mysql
   // cd /var/mysql && sudo ln -s /Applications/XAMPP/xamppfiles/var/mysql/mysql.sock
   'db' => array(
     'driver' => 'Pdo',
     'dsn' => "mysql:dbname=blockstarter;host=localhost",
     'username' => 'root',
     'password' => 'root',
     'driver_options' => array(
       PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
     ),
   ),
   'ip' => '112.210.106.129',
   'email' => 'gigamike@gigamike.net',
   'staticSalt' => 'oYiwd1SQL4UopvW',
   'aws' => [
     'access_key'      => '',
     'secret_access_key'  => '',
     'region' => 'us-east-1',
    ],
    'smptp' => [
      'host' => 'email-smtp.us-east-1.amazonaws.com',
      'port' => 25,
      'username' => '',
      'password' => '',
     ],
    'pathProjectPhoto' => [
      'absolutePath' => getcwd() . "/public_html/img/project/",
      'relativePath' => "/img/project/",
    ],
    'pathSupplier' => [
      'absolutePath' => getcwd() . "/public_html/img/supplier/",
      'relativePath' => "/img/supplier/",
    ],
    'ethereum' => [
      'rpc' => 'http://127.0.0.1:7545/',
      'public_address' => '0xfdE49d3002F2BBA94182Ad274C7cA58C518AF1ca',
      'project' => [
        'abi' => '[{"constant":true,"inputs":[],"name":"getMilestonesCount","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"contributors","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"getSummary","outputs":[{"internalType":"uint256","name":"","type":"uint256"},{"internalType":"uint256","name":"","type":"uint256"},{"internalType":"uint256","name":"","type":"uint256"},{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"manager","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"string","name":"description","type":"string"},{"internalType":"string","name":"comments","type":"string"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"value","type":"uint256"}],"name":"createMilestone","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"contributorsCount","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"minimumContribution","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"contribute","outputs":[],"payable":true,"stateMutability":"payable","type":"function"},{"constant":true,"inputs":[{"internalType":"uint256","name":"","type":"uint256"}],"name":"milestones","outputs":[{"internalType":"string","name":"description","type":"string"},{"internalType":"string","name":"comments","type":"string"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"value","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint256","name":"minimum","type":"uint256"},{"internalType":"address","name":"creator","type":"address"}],"payable":false,"stateMutability":"nonpayable","type":"constructor"}]',
        'bytecode' => '0x608060405234801561001057600080fd5b50604051610b60380380610b608339818101604052604081101561003357600080fd5b81019080805190602001909291908051906020019092919050505080600160006101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff160217905550816002819055505050610ab9806100a76000396000f3fe6080604052600436106100865760003560e01c80634f779655116100595780634f7796551461020a5780637569b3d714610393578063937e09b1146103be578063d7bb99ba146103e9578063e89e4ed6146103f357610086565b80630b49351c1461008b5780631f6d4942146100b65780634051ddac14610147578063481c6a75146101b3575b600080fd5b34801561009757600080fd5b506100a061054d565b6040518082815260200191505060405180910390f35b3480156100c257600080fd5b50610105600480360360208110156100d957600080fd5b81019080803573ffffffffffffffffffffffffffffffffffffffff169060200190929190505050610559565b604051808273ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200191505060405180910390f35b34801561015357600080fd5b5061015c61058c565b604051808581526020018481526020018381526020018273ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200194505050505060405180910390f35b3480156101bf57600080fd5b506101c86105e4565b604051808273ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200191505060405180910390f35b34801561021657600080fd5b506103916004803603608081101561022d57600080fd5b810190808035906020019064010000000081111561024a57600080fd5b82018360208201111561025c57600080fd5b8035906020019184600183028401116401000000008311171561027e57600080fd5b91908080601f016020809104026020016040519081016040528093929190818152602001838380828437600081840152601f19601f820116905080830192505050505050509192919290803590602001906401000000008111156102e157600080fd5b8201836020820111156102f357600080fd5b8035906020019184600183028401116401000000008311171561031557600080fd5b91908080601f016020809104026020016040519081016040528093929190818152602001838380828437600081840152601f19601f820116905080830192505050505050509192919290803573ffffffffffffffffffffffffffffffffffffffff1690602001909291908035906020019092919050505061060a565b005b34801561039f57600080fd5b506103a8610768565b6040518082815260200191505060405180910390f35b3480156103ca57600080fd5b506103d361076e565b6040518082815260200191505060405180910390f35b6103f1610774565b005b3480156103ff57600080fd5b5061042c6004803603602081101561041657600080fd5b8101908080359060200190929190505050610814565b6040518080602001806020018573ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168152602001848152602001838103835287818151815260200191508051906020019080838360005b838110156104a857808201518184015260208101905061048d565b50505050905090810190601f1680156104d55780820380516001836020036101000a031916815260200191505b50838103825286818151815260200191508051906020019080838360005b8381101561050e5780820151818401526020810190506104f3565b50505050905090810190601f16801561053b5780820380516001836020036101000a031916815260200191505b50965050505050505060405180910390f35b60008080549050905090565b60036020528060005260406000206000915054906101000a900473ffffffffffffffffffffffffffffffffffffffff1681565b6000806000806002543073ffffffffffffffffffffffffffffffffffffffff1631600080549050600160009054906101000a900473ffffffffffffffffffffffffffffffffffffffff16935093509350935090919293565b600160009054906101000a900473ffffffffffffffffffffffffffffffffffffffff1681565b600160009054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff161461066457600080fd5b61066c6109a1565b60405180608001604052808681526020018581526020018473ffffffffffffffffffffffffffffffffffffffff1681526020018381525090506000819080600181540180825580915050906001820390600052602060002090600402016000909192909190915060008201518160000190805190602001906106ef9291906109df565b50602082015181600101908051906020019061070c9291906109df565b5060408201518160020160006101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff160217905550606082015181600301555050505050505050565b60045481565b60025481565b600254341161078257600080fd5b33600360003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060006101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff160217905550600460008154809291906001019190505550565b6000818154811061082157fe5b9060005260206000209060040201600091509050806000018054600181600116156101000203166002900480601f0160208091040260200160405190810160405280929190818152602001828054600181600116156101000203166002900480156108cd5780601f106108a2576101008083540402835291602001916108cd565b820191906000526020600020905b8154815290600101906020018083116108b057829003601f168201915b505050505090806001018054600181600116156101000203166002900480601f01602080910402602001604051908101604052809291908181526020018280546001816001161561010002031660029004801561096b5780601f106109405761010080835404028352916020019161096b565b820191906000526020600020905b81548152906001019060200180831161094e57829003601f168201915b5050505050908060020160009054906101000a900473ffffffffffffffffffffffffffffffffffffffff16908060030154905084565b60405180608001604052806060815260200160608152602001600073ffffffffffffffffffffffffffffffffffffffff168152602001600081525090565b828054600181600116156101000203166002900490600052602060002090601f016020900481019282601f10610a2057805160ff1916838001178555610a4e565b82800160010185558215610a4e579182015b82811115610a4d578251825591602001919060010190610a32565b5b509050610a5b9190610a5f565b5090565b610a8191905b80821115610a7d576000816000905550600101610a65565b5090565b9056fea265627a7a72315820939277fb4a427b52e5f9c375656ccd9f31f7600611e620644f630b9020c8dc3764736f6c634300050b0032',
      ],
      'escrow' => [
        'abi' => '',
      ],
    ],
 );
