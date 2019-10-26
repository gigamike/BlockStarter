<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'Supplier\Controller\Index' => 'Supplier\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'supplier' => __DIR__ . '/../view',
				),
		),
);
