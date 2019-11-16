<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'User\Controller\Index' => 'User\Controller\IndexController',
				'User\Controller\Auth' => 'User\Controller\AuthController',
				'User\Controller\ForgotPassword' => 'User\Controller\ForgotPasswordController',
				'User\Controller\Registration' => 'User\Controller\RegistrationController',
				'User\Controller\Accounts' => 'User\Controller\AccountsController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'user' => __DIR__ . '/../view',
				),
		),
);
