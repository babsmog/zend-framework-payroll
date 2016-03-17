<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

class PersonnelTaskTable
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

  public function getPersonnelTask($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('personnel_task_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  public function savePersonnelTask(PersonnelTask $personnelTask)
  {
    $data = array(
      'personnel_id' => $personnelTask->personnelId,
      'task_id' => $personnelTask->taskId,
      'rate' => ((Float) $personnelTask->rate),
    );

    $id = (int) $personnelTask->personnelTaskId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getPersonnelTask($id)) {
        $this->tableGateway->update($data, array('personnel_task_id'=> $id));
      } else {
        throw new \Exception('PersonnelTask id does not exist');
      }
    }
  }

  public function deletePersonnelTask($id)
  {
    $this->tableGateway->delete(array('personnel_task_id' => (int) $id));
  }
}
