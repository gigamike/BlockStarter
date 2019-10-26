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
    'ethereumRpcServer' => 'http://127.0.0.1:7545/',
 );
