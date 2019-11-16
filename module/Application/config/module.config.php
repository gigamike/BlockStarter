<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'terms-of-use' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/terms-of-use',
                    'defaults' => array(
                        'controller' => 'Page\Controller\Index',
                        'action'     => 'termsOfUse',
                    ),
                ),
            ),
            'privacy-policy' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/privacy-policy',
                    'defaults' => array(
                        'controller' => 'Page\Controller\Index',
                        'action'     => 'privacyPolicy',
                    ),
                ),
            ),
            'about' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/about',
                    'defaults' => array(
                        'controller' => 'Page\Controller\Index',
                        'action'     => 'about',
                    ),
                ),
            ),
            'contact' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/contact',
                    'defaults' => array(
                        'controller' => 'Contact\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'registration' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/registration[/:action][/:id][/page/:page][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                        'search_by' => '.*',
                    ),
                    'defaults' => array(
                      'controller' => 'User\Controller\Registration',
                      'action'     => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/login[/:action][/:id][/page/:page][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                        'search_by' => '.*',
                    ),
                    'defaults' => array(
                      'controller' => 'User\Controller\Auth',
                      'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'forgot-password' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/forgot-password',
                    'defaults' => array(
                        'controller' => 'User\Controller\ForgotPassword',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin[/:action][/:id][/page/:page][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'search_by' => '.*',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'accounts' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/accounts[/:action][/:id][/page/:page][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'search_by' => '.*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Accounts',
                        'action'     => 'index',
                    ),
                ),
            ),
            'chatbot' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/chatbot[/:action][/:id][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'search_by' => '.*',
                    ),
                    'defaults' => array(
                        'controller' => 'Chatbot\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'project' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/project[/:action][/:id][/search_by/:search_by]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '.*',
                        'search_by' => '.*',
                    ),
                    'defaults' => array(
                        'controller' => 'Project\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'contact' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/contact',
                    'defaults' => array(
                        'controller' => 'Contact\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'suppliers' => array(
              'type' => 'segment',
              'options' => array(
                'route'    => '/suppliers/[:action][/:id][/page/:page][/search_by/:search_by]',
                'constraints' => array(
                  'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'id' => '[0-9]+',
                  'page' => '[0-9]+',
                  'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'order' => 'ASC|DESC',
                  'search_by' => '.*',
                ),
                'defaults' => array(
                  'controller' => 'Supplier\Controller\Index',
                  'action'     => 'index',
                ),
              ),
            ),
            'supplier' => array(
              'type' => 'segment',
              'options' => array(
                'route'    => '/supplier/[:action][/:id][/page/:page][/search_by/:search_by]',
                'constraints' => array(
                  'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'id' => '[0-9]+',
                  'page' => '[0-9]+',
                  'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'order' => 'ASC|DESC',
                  'search_by' => '.*',
                ),
                'defaults' => array(
                  'controller' => 'Supplier\Controller\Index',
                  'action'     => 'index',
                ),
              ),
            ),
            'member' => array(
              'type' => 'segment',
              'options' => array(
                'route'    => '/member/[:action][/:id][/page/:page][/search_by/:search_by]',
                'constraints' => array(
                  'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'id' => '[0-9]+',
                  'page' => '[0-9]+',
                  'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'order' => 'ASC|DESC',
                  'search_by' => '.*',
                ),
                'defaults' => array(
                  'controller' => 'Member\Controller\Index',
                  'action'     => 'index',
                ),
              ),
            ),
            'affiliate' => array(
              'type' => 'segment',
              'options' => array(
                'route'    => '/affiliate/[:action][/:id][/page/:page][/search_by/:search_by]',
                'constraints' => array(
                  'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'id' => '[0-9]+',
                  'page' => '[0-9]+',
                  'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'order' => 'ASC|DESC',
                  'search_by' => '.*',
                ),
                'defaults' => array(
                  'controller' => 'Affiliate\Controller\Index',
                  'action'     => 'index',
                ),
              ),
            ),
            'invest' => array(
              'type' => 'segment',
              'options' => array(
                'route'    => '/invest/[:action][/:id][/page/:page][/search_by/:search_by]',
                'constraints' => array(
                  'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'id' => '[0-9]+',
                  'page' => '[0-9]+',
                  'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  'order' => 'ASC|DESC',
                  'search_by' => '.*',
                ),
                'defaults' => array(
                  'controller' => 'Invest\Controller\Index',
                  'action'     => 'index',
                ),
              ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
              'cron-test' => array(
                'options' => array(
                  'route'    => 'cron-test',
                  'defaults' => array(
                    'controller' => 'Cron\Controller\Index',
                    'action'     => 'index'
                  ),
                ),
              ),
            ),
        ),
    ),
);
