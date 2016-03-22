<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\PersonnelTask;
use Payroll\Form\PersonnelTaskForm;

class PersonnelTaskController extends AbstractActionController
{
  protected $personnelTaskTable;
  protected $personnelTable;
  protected $taskTable;

  /*
  Corresponds to the index of the route.
  */
  public function indexAction()
  {

    /*
    With Zend Framework 2, in order to set variables in the view, we return a ViewModel instance where the first parameter
    of the constructor is an array from the action containing data we need. These are then automatically passed to the view
    script. The ViewModel object also allows us to change the view script that is used, but the default is to use
    {controller name}/{action name}. We can now fill in the index.phtml view script.
    */
    return new ViewModel(array(
      'personnelTasks' => $this->getPersonnelTaskTable()->fetchAll(),
      'personnelTable' => $this->getPersonnelTable(),
      'taskTable' => $this->getTaskTable(),
    ));
  }

  /*
  Corresponds to the action:add of the route.
  */
  public function addAction()
  {
    $form = new PersonnelTaskForm();

    /*
    Gets a list of all personnels in the database table
    and setPersonnelArray which adds a select input to the form created with
    the selection list being that of the personnels' full names and ids.
    */
    //////////////////////////////////////////////////
    $personnels = $this->getPersonnelTable()->fetchAll();
    $personnel_array = array();
    foreach ($personnels as $personnel) :
      $personnel_array[$personnel->personnelId] = $personnel->personnelId.': '.$personnel->fName.' '.$personnel->lName;
    endforeach;
    $form->setPersonnelArray($personnel_array);
    /////////////////////////////////////////////////


    /*
    Gets a list of all tasks in the database table
    and setPersonnelArray which adds a select input to the form created with
    the selection list being that of the tasks' names.
    */
    /////////////////////////////////////////////////////
    $tasks = $this->getTaskTable()->fetchAll();
    $task_array = array();
    foreach ($tasks as $task) :
      $task_array[$task->taskId] = $task->taskId.': '.$task->taskName;
    endforeach;
    $form->setTaskArray($task_array);
    /////////////////////////////////////////////////////
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $personnelTask = new PersonnelTask();
      $form->setInputFilter($personnelTask->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $personnelTask->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.

        $this->getPersonnelTaskTable()->savePersonnelTask($personnelTask);

        return $this->redirect()->toRoute('personnel-task');
      }
    }
    return array('form' => $form);
  }

  /*
  Corresponds to the action:edit of the route.
  */
  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);
    /* If id from route/action/id is zero redirect to add form. */
    if (!$id) {
      return $this->redirect()->toRoute('personnel-task',array(
        'action' => 'add'
      ));
    }

    try {
      $personnelTask = $this->getPersonnelTaskTable()->getPersonnelTask($id);
    }
    catch (\Exception $ex) {
      return $this->redirect()->toRoute('personnel-task',array(
        'action' => 'index'
      ));
    }

    $form = new PersonnelTaskForm();

    /*
    Gets a list of all personnels in the database table
    and setPersonnelArray which adds a select input to the form created with
    the selection list being that of the personnels' full name and id.
    */
    //////////////////////////////////////////////////
    $personnels = $this->getPersonnelTable()->fetchAll();
    $personnel_array = array();
    foreach ($personnels as $personnel) :
      $personnel_array[$personnel->personnelId] = $personnel->personnelId.': '.$personnel->fName.' '.$personnel->lName;
    endforeach;
    $form->setPersonnelArray($personnel_array);
    /////////////////////////////////////////////////

    /*
    Gets a list of all tasks in the database table
    and setTaskArray which adds a select input to the form created with
    the selection list being that of the tasks' name.
    */
    /////////////////////////////////////////////////////
    $tasks = $this->getTaskTable()->fetchAll();
    $task_array = array();
    foreach ($tasks as $task) :
      $task_array[$task->taskId] = $task->taskId.': '.$task->taskName;
    endforeach;
    $form->setTaskArray($task_array);
    /////////////////////////////////////////////////////

    /* Sets the forms to the data in the pesonnel-task model */
    $form->get('personnel_task_id')->setAttribute('value',$personnelTask->personnelTaskId);
    $form->get('personnel_id')->setAttribute('value',$personnelTask->personnelId);
    $form->get('task_id')->setAttribute('value',$personnelTask->taskId);
    $form->get('rate')->setAttribute('value',$personnelTask->rate);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($personnelTask->getInputFilter());
      $form->setData($request->getPost());

      /* If the form validates put all current form data into data array to be saved */
      if ($form->isValid()) {

        $personnelTask->exchangeArray($form->getData());

        $this->getPersonnelTaskTable()->savePersonnelTask($personnelTask);

        return $this->redirect()->toRoute('personnel-task',array(
          'action' => 'index'
        ));
      }
    }

    /* Pass these key value pairings as identifiers into the edit view. */
    return array(
      'id' => $id,
      'form' => $form,
    );

  }

  /*
  Corresponds to the action:delete of the route.
  */
  public function deleteAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);
    /* If id from route/action/id is zero redirect to route index. */
    if (!$id) {
      return $this->redirect()->toRoute('personnel-task');
    }

    $request = $this->getRequest();
    /* If request method is POST and 'del' is Yes delete row from database table. */
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getPersonnelTaskTable()->deletePersonnelTask($id);
      }

      return $this->redirect()->toRoute('personnel-task');
    }

    /* Pass these key value pairings as identifiers into the delete view. */
    return array(
      'id' => $id,
      'personnelTask' => $this->getPersonnelTaskTable()->getPersonnelTask($id)
    );
  }

  /*
  This method uses the services manager to get an instance of a Table object to
  access data in the database. This instance can be used throughout the Controller
  without creating new instances.
  */
  public function getPersonnelTaskTable()
  {
    if (!$this->personnelTaskTable) {
      $sm = $this->getServiceLocator();
      $this->personnelTaskTable = $sm->get('Payroll\Model\PersonnelTaskTable');
    }
    return $this->personnelTaskTable;
  }

  /*
  This method uses the services manager to get an instance of a Table object to
  access data in the database. This instance can be used throughout the Controller
  without creating new instances.
  */
  public function getPersonnelTable()
  {
    if (!$this->personnelTable) {
      $sm = $this->getServiceLocator();
      $this->personnelTable = $sm->get('Payroll\Model\PersonnelTable');
    }
    return $this->personnelTable;
  }

  /*
  This method uses the services manager to get an instance of a Table object to
  access data in the database. This instance can be used throughout the Controller
  without creating new instances.
  */
  public function getTaskTable()
  {
    if (!$this->taskTable) {
      $sm = $this->getServiceLocator();
      $this->taskTable = $sm->get('Payroll\Model\TaskTable');
    }
    return $this->taskTable;
  }

}
