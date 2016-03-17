<?php

namespace Payroll;

use Payroll\Model\Task;
use Payroll\Model\TaskTable;

use Payroll\Model\Location;
use Payroll\Model\LocationTable;

use Payroll\Model\Personnel;
use Payroll\Model\PersonnelTable;

use Payroll\Model\Address;
use Payroll\Model\AddressTable;

use Payroll\Model\PersonnelTask;
use Payroll\Model\PersonnelTaskTable;

use Payroll\Model\WorkDone;
use Payroll\Model\WorkDoneTable;

use Payroll\Model\Pay;
use Payroll\Model\PayTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
  public function getAutoloaderConfig()
  {
    return array(
      'Zend\Loader\ClassMapAutoloader' => array(
        __DIR__ . '/autoload_classmap.php',
      ),
      'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
        ),
      ),
    );
  }

  public function getConfig()
  {
    return include __DIR__ . '/config/module.config.php';
  }

  public function getServiceConfig()
  {
    return array(
      'factories' => array(
        'Payroll\Model\TaskTable' => function($sm) {
          $tableGateway = $sm->get('TaskTableGateway');
          $table = new  TaskTable($tableGateway);
          return $table;
        },
        'TaskTableGateway' => function($sm) {
          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
          $resultSetPrototype = new ResultSet();
          $resultSetPrototype->setArrayObjectPrototype(new Task());
          return new TableGateway('task',$dbAdapter, null, $resultSetPrototype);
        },


        'Payroll\Model\LocationTable' => function($sm) {
          $tableGateway = $sm->get('LocationTableGateway');
          $table = new  LocationTable($tableGateway);
          return $table;
        },
        'LocationTableGateway' => function($sm) {
          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
          $resultSetPrototype = new ResultSet();
          $resultSetPrototype->setArrayObjectPrototype(new Location());
          return new TableGateway('location',$dbAdapter, null, $resultSetPrototype);
        },

        'Payroll\Model\PersonnelTable' => function($sm) {
          $tableGateway = $sm->get('PersonnelTableGateway');
          $table = new  PersonnelTable($tableGateway);
          return $table;
        },
        'PersonnelTableGateway' => function($sm) {
          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
          $resultSetPrototype = new ResultSet();
          $resultSetPrototype->setArrayObjectPrototype(new Personnel());
          return new TableGateway('personnel',$dbAdapter, null, $resultSetPrototype);
        },

        'Payroll\Model\AddressTable' => function($sm) {
          $tableGateway = $sm->get('AddressTableGateway');
          $table = new  AddressTable($tableGateway);
          return $table;
        },
        'AddressTableGateway' => function($sm) {
          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
          $resultSetPrototype = new ResultSet();
          $resultSetPrototype->setArrayObjectPrototype(new Address());
          return new TableGateway('address',$dbAdapter, null, $resultSetPrototype);
        },

        'Payroll\Model\PersonnelTaskTable' => function($sm) {
          $tableGateway = $sm->get('PersonnelTaskTableGateway');
          $table = new  PersonnelTaskTable($tableGateway);
          return $table;
        },
        'PersonnelTaskTableGateway' => function($sm) {
          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
          $resultSetPrototype = new ResultSet();
          $resultSetPrototype->setArrayObjectPrototype(new PersonnelTask());
          return new TableGateway('personnel_task',$dbAdapter, null, $resultSetPrototype);
        },

        'Payroll\Model\WorkDoneTable' => function($sm) {
          $tableGateway = $sm->get('WorkDoneTableGateway');
          $table = new  WorkDoneTable($tableGateway);
          return $table;
        },
        'WorkDoneTableGateway' => function($sm) {
          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
          $resultSetPrototype = new ResultSet();
          $resultSetPrototype->setArrayObjectPrototype(new WorkDone());
          return new TableGateway('work_done',$dbAdapter, null, $resultSetPrototype);
        },

        'Payroll\Model\PayTable' => function($sm) {
          $tableGateway = $sm->get('PayTableGateway');
          $table = new  PayTable($tableGateway);
          return $table;
        },
        'PayTableGateway' => function($sm) {
          $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
          $resultSetPrototype = new ResultSet();
          $resultSetPrototype->setArrayObjectPrototype(new Pay());
          return new TableGateway('pay',$dbAdapter, null, $resultSetPrototype);
        },

      ),
    );
  }
}
