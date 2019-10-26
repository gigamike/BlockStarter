<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'SmartContract\Controller\Index' => 'SmartContract\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'smart-contract' => __DIR__ . '/../view',
				),
		),
);
