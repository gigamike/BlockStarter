<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'Project\Controller\Index' => 'Project\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'project' => __DIR__ . '/../view',
				),
		),
);
