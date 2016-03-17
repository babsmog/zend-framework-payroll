<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

class PersonnelTable
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

  public function getPersonnel($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('personnel_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  public function savePersonnel(Personnel $personnel)
  {
    $data = array(
      'fname' => $personnel->fName,
      'lname' => $personnel->lName,
      'age' => ((int) $personnel->age),
      'address' => $personnel->address,
      'gender' => $personnel->gender,
    );

    $id = (int) $personnel->personnelId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getPersonnel($id)) {
        $this->tableGateway->update($data, array('personnel_id'=> $id));
      } else {
        throw new \Exception('Personnel id does not exist');
      }
    }
  }

  public function deletePersonnel($id)
  {
    $this->tableGateway->delete(array('personnel_id' => (int) $id));
  }
}
