<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'Affiliate\Controller\Index' => 'Affiliate\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'affiliate' => __DIR__ . '/../view',
				),
		),
);
