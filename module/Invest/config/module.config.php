<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'Invest\Controller\Index' => 'Invest\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'invest' => __DIR__ . '/../view',
				),
		),
);
