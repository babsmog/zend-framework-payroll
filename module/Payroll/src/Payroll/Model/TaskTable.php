<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

class TaskTable
{
  protected $tableGateway;

  public function __construct(TableGateway $tableGateway)
  {
    $this->tableGateway = $tableGateway;
  }

  public function fetchAll()
  {
    $resultSet = $this->tableGateway->select();
    return $resultSet;
  }

  public function getTask($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('task_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  public function getTask2($taskName)
  {
    $taskName = ucwords(strtolower(preg_replace("!\s+!",' ',$taskName)));
    $rowset = $this->tableGateway->select(array('task_name' => $taskName));
    $row = $rowset->current();
    if (!$row) {
      return null;
    }
    return $row;
  }

  public function saveTask(Task $task)
  {
    $data = array(
      'task_name' => $task->taskName,
    );

    $id = (int) $task->taskId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getTask($id)) {
        $this->tableGateway->update($data, array('task_id'=> $id));
      } else {
        throw new \Exception('Task id does not exist');
      }
    }
  }

  public function deleteTask($id)
  {
    $this->tableGateway->delete(array('task_id' => (int) $id));
  }
}
