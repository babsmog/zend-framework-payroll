<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

class LocationTable
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

  public function getLocation($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('location_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  public function getLocation2($locationName)
  {
    $locationName = ucwords(strtolower(preg_replace("!\s+!",' ',$locationName)));
    $rowset = $this->tableGateway->select(array('location_name' => $locationName));
    $row = $rowset->current();
    if (!$row) {
      return null;
    }
    return $row;
  }

  public function saveLocation(Location $location)
  {
    $data = array(
      'location_name' => $location->locationName,
    );

    $id = (int) $location->locationId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getLocation($id)) {
        $this->tableGateway->update($data, array('location_id'=> $id));
      } else {
        throw new \Exception('Location id does not exist');
      }
    }
  }

  public function deleteLocation($id)
  {
    $this->tableGateway->delete(array('location_id' => (int) $id));
  }
}
