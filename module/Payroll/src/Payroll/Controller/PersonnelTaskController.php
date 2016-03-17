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

  public function indexAction()
  {

    return new ViewModel(array(
      'personnelTasks' => $this->getPersonnelTaskTable()->fetchAll(),
      'personnelTable' => $this->getPersonnelTable(),
      'taskTable' => $this->getTaskTable(),
    ));
  }

  public function addAction()
  {
    $form = new PersonnelTaskForm();
    //////////////////////////////////////////////////
    $personnels = $this->getPersonnelTable()->fetchAll();
    $personnel_array = array();
    foreach ($personnels as $personnel) :
      $personnel_array[$personnel->personnelId] = $personnel->personnelId.': '.$personnel->fName.' '.$personnel->lName;
    endforeach;
    $form->setPersonnelArray($personnel_array);
    /////////////////////////////////////////////////

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
        $personnelTask->exchangeArray($form->getData());

        $this->getPersonnelTaskTable()->savePersonnelTask($personnelTask);

        return $this->redirect()->toRoute('personnel-task');
      }
    }
    return array('form' => $form);
  }

  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);

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

    //////////////////////////////////////////////////
    $personnels = $this->getPersonnelTable()->fetchAll();
    $personnel_array = array();
    foreach ($personnels as $personnel) :
      $personnel_array[$personnel->personnelId] = $personnel->personnelId.': '.$personnel->fName.' '.$personnel->lName;
    endforeach;
    $form->setPersonnelArray($personnel_array);
    /////////////////////////////////////////////////

    /////////////////////////////////////////////////////
    $tasks = $this->getTaskTable()->fetchAll();
    $task_array = array();
    foreach ($tasks as $task) :
      $task_array[$task->taskId] = $task->taskId.': '.$task->taskName;
    endforeach;
    $form->setTaskArray($task_array);
    /////////////////////////////////////////////////////

    $form->get('personnel_id')->setAttribute('value',$personnelTask->personnelId);
    $form->get('task_id')->setAttribute('value',$personnelTask->taskId);
    $form->get('rate')->setAttribute('value',$personnelTask->rate);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($personnelTask->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {

        $personnelTask->exchangeArray($form->getData());

        $personnelTask->personnelTaskId = $id;
        $this->getPersonnelTaskTable()->savePersonnelTask($personnelTask);

        return $this->redirect()->toRoute('personnel-task',array(
          'action' => 'index'
        ));
      }
    }

    return array(
      'id' => $id,
      'form' => $form,
    );

  }


  public function deleteAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);
    if (!$id) {
      return $this->redirect()->toRoute('personnel-task');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getPersonnelTaskTable()->deletePersonnelTask($id);
      }

      return $this->redirect()->toRoute('personnel-task');
    }

    return array(
      'id' => $id,
      'personnelTask' => $this->getPersonnelTaskTable()->getPersonnelTask($id)
    );
  }

  public function getPersonnelTaskTable()
  {
    if (!$this->personnelTaskTable) {
      $sm = $this->getServiceLocator();
      $this->personnelTaskTable = $sm->get('Payroll\Model\PersonnelTaskTable');
    }
    return $this->personnelTaskTable;
  }

  public function getPersonnelTable()
  {
    if (!$this->personnelTable) {
      $sm = $this->getServiceLocator();
      $this->personnelTable = $sm->get('Payroll\Model\PersonnelTable');
    }
    return $this->personnelTable;
  }

  public function getTaskTable()
  {
    if (!$this->taskTable) {
      $sm = $this->getServiceLocator();
      $this->taskTable = $sm->get('Payroll\Model\TaskTable');
    }
    return $this->taskTable;
  }

}
