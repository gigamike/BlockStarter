<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'Test\Controller\Index' => 'Test\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'test' => __DIR__ . '/../view',
				),
		),
);
