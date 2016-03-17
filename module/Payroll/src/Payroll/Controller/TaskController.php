<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Task;
use Payroll\Form\TaskForm;

class TaskController extends AbstractActionController
{
  protected $taskTable;

  public function indexAction()
  {
    return new ViewModel(array(
      'tasks' => $this->getTaskTable()->fetchAll(),
    ));
  }

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
        $task->exchangeArray($form->getData());
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

  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);

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
    $form->get('task_name')->setAttribute('value',$task->taskName);
    //$form->bind($task);

    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($task->getInputFilter());

      $form->setData($request->getPost());




      if ($form->isValid()) {
        $task->taskId = $id;
        $this->getTaskTable()->saveTask($task);


        return $this->redirect()->toRoute('task',array(
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
      return $this->redirect()->toRoute('task');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getTaskTable()->deleteTask($id);
      }

      return $this->redirect()->toRoute('task');
    }

    return array(
      'id' => $id,
      'task' => $this->getTaskTable()->getTask($id)
    );
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
