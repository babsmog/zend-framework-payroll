<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

class WorkDoneTable
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

  public function fetchAll2($period)
  {
    $period = (int) $period;
    $resultSet = $this->tableGateway->select(array('period' => $period));
    return $resultSet;
  }

  public function getWorkDone($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('work_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  public function saveWorkDone(WorkDone $work)
  {

    //Auto calculate period base on dateDone
    $startDate = date_create(date('Y').'-01-01');
    $periodDuration = 14;
    $difference = (date_diff($startDate,date_create($work->dateDone))->format("%a"));
    $work->period = ((int)floor($difference/14))+1;
    ////////////////////////


    //Auto set year
    $work->year = date('Y');
    /////////////////////////////

    $data = array(
      'personnel_task_id' => $work->personnelTaskId,
      'date_done' => $work->dateDone,
      'period' => ((int) $work->period),
      'hrs_worked' => ((Float) $work->hoursWorked),
      'location_id' => $work->locationId,
      'year' => ((int) $work->year),
    );

    $id = (int) $work->workId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getWorkDone($id)) {
        $this->tableGateway->update($data, array('work_id'=> $id));
      } else {
        throw new \Exception('WorkDone id does not exist');
      }
    }
  }

  public function deleteWorkDone($id)
  {
    $this->tableGateway->delete(array('work_id' => (int) $id));
  }
}
