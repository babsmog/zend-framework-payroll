<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\WorkDone;
use Payroll\Form\WorkDoneForm;
use Payroll\Model\Pay;

class WorkDoneController extends AbstractActionController
{
  protected $workDoneTable;
  protected $personnelTaskTable;
  protected $personnelTable;
  protected $taskTable;
  protected $locationTable;
  protected $payTable;

  public function indexAction()
  {

    return new ViewModel(array(
      'works' => $this->getWorkDoneTable()->fetchAll(),
      'personnelTaskTable' => $this->getPersonnelTaskTable(),
      'locationTable' => $this->getLocationTable(),
      'personnelTable' => $this->getPersonnelTable(),
      'taskTable' => $this->getTaskTable(),
    ));
  }


  public function addAction()
  {
    $form = new WorkDoneForm();
    //////////////////////////////////////////////////
    $personnelTasks = $this->getPersonnelTaskTable()->fetchAll();
    $personnelTask_array = array();
    foreach ($personnelTasks as $personnelTask) :
      $personnel = $this->getPersonnelTable()->getPersonnel($personnelTask->personnelId);
      $task = $this->getTaskTable()->getTask($personnelTask->taskId);
      $personnelTask_array[$personnelTask->personnelTaskId] = $personnel->personnelId.' :: '.$personnel->fName.' '.$personnel->lName
      .' - '.$task->taskName.'($'.$personnelTask->rate.')';
    endforeach;
    $form->setPersonnelTaskArray($personnelTask_array);
    //////////////////////////////////////////////////////

    /////////////////////////////////////////////////////
    $locations = $this->getLocationTable()->fetchAll();
    $location_array = array();
    foreach ($locations as $location) :
      $location_array[$location->locationId] = $location->locationName;
    endforeach;
    $form->setLocationArray($location_array);
    /////////////////////////////////////////////////////
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $workDone = new WorkDone();
      $form->setInputFilter($workDone->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $workDone->exchangeArray($form->getData());

        $this->getWorkDoneTable()->saveWorkDone($workDone);

        return $this->redirect()->toRoute('work-done');
      }
    }
    return array('form' => $form);
  }


  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);

    if (!$id) {
      return $this->redirect()->toRoute('work-done',array(
        'action' => 'add'
      ));
    }

    try {
      $workDone = $this->getWorkDoneTable()->getWorkDone($id);
    }
    catch (\Exception $ex) {
      return $this->redirect()->toRoute('work-done',array(
        'action' => 'index'
      ));
    }

    $form = new WorkDoneForm();

    //////////////////////////////////////////////////
    $personnelTasks = $this->getPersonnelTaskTable()->fetchAll();
    $personnelTask_array = array();
    foreach ($personnelTasks as $personnelTask) :
      $personnel = $this->getPersonnelTable()->getPersonnel($personnelTask->personnelId);
      $task = $this->getTaskTable()->getTask($personnelTask->taskId);
      $personnelTask_array[$personnelTask->personnelTaskId] = $personnel->personnelId.' :: '.$personnel->fName.' '.$personnel->lName
      .' - '.$task->taskName.'($'.$personnelTask->rate.')';
    endforeach;
    $form->setPersonnelTaskArray($personnelTask_array);
    //////////////////////////////////////////////////////

    /////////////////////////////////////////////////////
    $locations = $this->getLocationTable()->fetchAll();
    $location_array = array();
    foreach ($locations as $location) :
      $location_array[$location->locationId] = $location->locationName;
    endforeach;
    $form->setLocationArray($location_array);
    /////////////////////////////////////////////////////

    $form->get('personnel_task_id')->setAttribute('value',$workDone->personnelTaskId);
    $form->get('date_done')->setAttribute('value',$workDone->dateDone);
    $form->get('hrs_worked')->setAttribute('value',$workDone->hoursWorked);
    $form->get('location_id')->setAttribute('value',$workDone->locationId);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($workDone->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {

        $workDone->exchangeArray($form->getData());

        $workDone->workId = $id;
        $this->getWorkDoneTable()->saveWorkDone($workDone);

        return $this->redirect()->toRoute('work-done',array(
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
      return $this->redirect()->toRoute('work-done');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getWorkDoneTable()->deleteWorkDone($id);
      }

      return $this->redirect()->toRoute('work-done');
    }

    return array(
      'id' => $id,
      'workDone' => $this->getWorkDoneTable()->getWorkDone($id)
    );
  }

  public function calculateAction()
  {
    $lastPeriod = 0;
    $year = 0;

    //Auto calculate current period based current date time
    $startDate = date_create(date('Y').'-01-01');
    $today = date_create(date('Y-m-d'));
    $periodDuration = 14;
    $difference = (date_diff($startDate,$today)->format("%a"));
    $currentPeriod = ((int)floor($difference/14))+1;
    ////////////////////////
    if ($currentPeriod==1) {
      $lastPeriod = 27;
      $year = ((int) date('Y')) - 1;
    }
    else {
      $lastPeriod = $currentPeriod-1;
      $year = ((int) date('Y'));
    }
    $worksDoneLastPeriod=$this->getWorkDoneTable()->fetchAll2($lastPeriod);
    $pays = array();
    foreach ($worksDoneLastPeriod as $work) :
      $personnelTask = $this->getPersonnelTaskTable()->getPersonnelTask($work->personnelTaskId);
      $personnelId = $personnelTask->personnelId;
      $rate = $personnelTask->rate;
      $hoursWorked = $work->hoursWorked;

      if (isset($pays[$personnelId])) {
        $pays[$personnelId] = $pays[$personnelId] + ($rate * $hoursWorked);
      }
      else {
        $pays[$personnelId] = ($rate * $hoursWorked);
      }
    endforeach;

    $ids = array_keys($pays);
    foreach ($ids as $id) :
      $payCheck = new Pay();
      $payCheck->payId = 0;
      $payCheck->personnelId = $id;
      $payCheck->amount = $pays[$id];
      $payCheck->period = $lastPeriod;
      $payCheck->year = $year;

      $this->getPayTable()->savePay($payCheck);
    endforeach;

    return $this->redirect()->toRoute('pay');
  }

  public function getWorkDoneTable()
  {
    if (!$this->workDoneTable) {
      $sm = $this->getServiceLocator();
      $this->workDoneTable = $sm->get('Payroll\Model\WorkDoneTable');
    }
    return $this->workDoneTable;
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

  public function getLocationTable()
  {
    if (!$this->locationTable) {
      $sm = $this->getServiceLocator();
      $this->locationTable = $sm->get('Payroll\Model\LocationTable');
    }
    return $this->locationTable;
  }

  public function getPayTable()
  {
    if (!$this->payTable) {
      $sm = $this->getServiceLocator();
      $this->payTable = $sm->get('Payroll\Model\PayTable');
    }
    return $this->payTable;
  }

}
