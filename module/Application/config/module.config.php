<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

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

    'navigation' => array(
      'default' => array(
        array(
            'label' => 'Home',
            'route' => 'home',
        ),
        array(
            'label' => 'Personnel',
            'route' => 'personnel',
            'pages' => array(
                array(
                    'label' => 'Add',
                    'route' => 'personnel',
                    'action' => 'add',
                ),
                array(
                    'label' => 'Edit',
                    'route' => 'personnel',
                    'action' => 'edit',
                ),
                array(
                    'label' => 'Delete',
                    'route' => 'personnel',
                    'action' => 'delete',
                ),
            ),
        ),

        array(
            'label' => 'Task',
            'route' => 'task',
            'pages' => array(
                array(
                    'label' => 'Add',
                    'route' => 'task',
                    'action' => 'add',
                ),
                array(
                    'label' => 'Edit',
                    'route' => 'task',
                    'action' => 'edit',
                ),
                array(
                    'label' => 'Delete',
                    'route' => 'task',
                    'action' => 'delete',
                ),
            ),
        ),

        array(
            'label' => 'Assign Task',
            'route' => 'personnel-task',
            'pages' => array(
                array(
                    'label' => 'Add',
                    'route' => 'personnel-task',
                    'action' => 'add',
                ),
                array(
                    'label' => 'Edit',
                    'route' => 'personnel-task',
                    'action' => 'edit',
                ),
                array(
                    'label' => 'Delete',
                    'route' => 'personnel-task',
                    'action' => 'delete',
                ),
            ),
        ),

        array(
            'label' => 'Work Done',
            'route' => 'work-done',
            'pages' => array(
                array(
                    'label' => 'Add',
                    'route' => 'work-done',
                    'action' => 'add',
                ),
                array(
                    'label' => 'Edit',
                    'route' => 'work-done',
                    'action' => 'edit',
                ),
                array(
                    'label' => 'Delete',
                    'route' => 'work-done',
                    'action' => 'delete',
                ),
            ),
        ),

        array(
            'label' => 'Pay',
            'route' => 'pay',
            'pages' => array(
                array(
                    'label' => 'Add',
                    'route' => 'pay',
                    'action' => 'add',
                ),
                array(
                    'label' => 'Edit',
                    'route' => 'pay',
                    'action' => 'edit',
                ),
                array(
                    'label' => 'Delete',
                    'route' => 'pay',
                    'action' => 'delete',
                ),
            ),
        ),

        array(
            'label' => 'Location',
            'route' => 'location',
            'pages' => array(
                array(
                    'label' => 'Add',
                    'route' => 'location',
                    'action' => 'add',
                ),
                array(
                    'label' => 'Edit',
                    'route' => 'location',
                    'action' => 'edit',
                ),
                array(
                    'label' => 'Delete',
                    'route' => 'location',
                    'action' => 'delete',
                ),
            ),
        ),

        array(
            'label' => 'Address',
            'route' => 'address',
            'pages' => array(
                array(
                    'label' => 'Add',
                    'route' => 'address',
                    'action' => 'add',
                ),
                array(
                    'label' => 'Edit',
                    'route' => 'address',
                    'action' => 'edit',
                ),
                array(
                    'label' => 'Delete',
                    'route' => 'address',
                    'action' => 'delete',
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
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
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
            'Application\Controller\Index' => Controller\IndexController::class
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
            ),
        ),
    ),
);
