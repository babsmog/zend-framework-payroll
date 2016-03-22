<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Task;
use Payroll\Form\TaskForm;

class TaskController extends AbstractActionController
{
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
      'tasks' => $this->getTaskTable()->fetchAll(),
    ));
  }

  /*
  Corresponds to the action:add of the route.
  */
  public function addAction()
  {
    $form = new TaskForm();
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $task = new Task();
      $form->setInputFilter($task->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $task->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.

        /*Checks to see if the task name already exist and if so redirects to the index of the task route. */
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $taskName = $form->getData()['task_name'];
                $taskRow = ($this->getTaskTable()->getTask2($taskName));
                if ($taskRow) {
                  return $this->redirect()->toRoute('task');
                }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->getTaskTable()->saveTask($task);

        return $this->redirect()->toRoute('task');
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
      return $this->redirect()->toRoute('task',array(
        'action' => 'add'
      ));
    }

    try {
      $task = $this->getTaskTable()->getTask($id);

    }
    catch (\Exception $ex) {
      return $this->redirect()->toRoute('task',array(
        'action' => 'index'
      ));
    }


    $form = new TaskForm();

    //The line below was not working as expected for some reason so I decided to do the extraction of the data manually.
    //$form->bind($task);

    $form->get('task_id')->setAttribute('value',$task->taskId);
    $form->get('task_name')->setAttribute('value',$task->taskName);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($task->getInputFilter()); //Sets inputfilter from entity object.

      $form->setData($request->getPost());




      if ($form->isValid()) {
        $task->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.
        $this->getTaskTable()->saveTask($task);


        return $this->redirect()->toRoute('task',array(
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
    /* If id from route/action/id is zero redirect to route index. */
    $id = (int) $this->params()->fromRoute('id',0);
    if (!$id) {
      return $this->redirect()->toRoute('task');
    }

    $request = $this->getRequest();
    /* If request method is POST and 'del' is Yes delete row from database table. */
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getTaskTable()->deleteTask($id);
      }

      return $this->redirect()->toRoute('task');
    }

    /* Pass these key value pairings as identifiers into the delete view. */
    return array(
      'id' => $id,
      'task' => $this->getTaskTable()->getTask($id)
    );
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
