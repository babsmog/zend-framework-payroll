<?php

return array(
  'controllers' => array(
    'invokables' => array(
      'Payroll\Controller\Task' => 'Payroll\Controller\TaskController',
      'Payroll\Controller\Location' => 'Payroll\Controller\LocationController',
      'Payroll\Controller\Personnel' => 'Payroll\Controller\PersonnelController',
      /*'Payroll\Controller\Address' => 'Payroll\Controller\AddressController',*/
      'Payroll\Controller\PersonnelTask' => 'Payroll\Controller\PersonnelTaskController',
      'Payroll\Controller\WorkDone' => 'Payroll\Controller\WorkDoneController',
      'Payroll\Controller\Pay' => 'Payroll\Controller\PayController',
    ),
  ),

  'router' => array(
    'routes' => array(
        'task' => array(
            'type'    => 'segment',
            'options' => array(
                'route'    => '/task[/:action][/:id]',
                'constraints' => array(
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'id'     => '[0-9]+',
                ),
                'defaults' => array(
                    'controller' => 'Payroll\Controller\Task',
                    'action'     => 'index',
                 ),
             ),
         ),

         'location' => array(
             'type'    => 'segment',
             'options' => array(
                 'route'    => '/location[/:action][/:id]',
                 'constraints' => array(
                     'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     'id'     => '[0-9]+',
                 ),
                 'defaults' => array(
                     'controller' => 'Payroll\Controller\Location',
                     'action'     => 'index',
                  ),
              ),
          ),

          'personnel' => array(
              'type'    => 'segment',
              'options' => array(
                  'route'    => '/personnel[/:action][/:id]',
                  'constraints' => array(
                      'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                      'id'     => '[0-9]+',
                  ),
                  'defaults' => array(
                      'controller' => 'Payroll\Controller\Personnel',
                      'action'     => 'index',
                   ),
               ),
           ),

            'personnel-task' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/personnel-task[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Payroll\Controller\PersonnelTask',
                        'action'     => 'index',
                     ),
                 ),
             ),

             'work-done' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/work-done[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Payroll\Controller\WorkDone',
                         'action'     => 'index',
                      ),
                  ),
              ),

              'pay' => array(
                  'type'    => 'segment',
                  'options' => array(
                      'route'    => '/pay[/:action][/:id]',
                      'constraints' => array(
                          'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                          'id'     => '[0-9]+',
                      ),
                      'defaults' => array(
                          'controller' => 'Payroll\Controller\Pay',
                          'action'     => 'index',
                       ),
                   ),
               ),

     ),
 ),


  'view_manager' => array(
    'template_path_stack' => array(
      'payroll' => __DIR__ . '/../view',
    ),
  ),
);
