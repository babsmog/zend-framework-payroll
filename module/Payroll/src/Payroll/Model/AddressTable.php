<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

class AddressTable
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

  public function createAddress($street_name,$community,$parish) {
    $address = new Address();
    $address->streetName = $street_name;
    $address->community = $community;
    $address->parish = $parish;
    $address->addressId = 0;
    $this->saveAddress($address);
  }

  public function getAddress($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('addr_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  public function getAddress2($street_name,$community,$parish)
  {
    $street_name = ucwords(strtolower(preg_replace("!\s+!",' ',$street_name)));
    $community = ucwords(strtolower(preg_replace("!\s+!",' ',$community)));
    $parish = ucwords(strtolower(preg_replace("!\s+!",' ',$parish)));

    $rowset = $this->tableGateway->select(array('street_name' => $street_name,'community' => $community, 'parish' => $parish));
    $row = $rowset->current();
    if (!$row) {
      return null;
    }
    return $row;
  }

  public function saveAddress(Address $address)
  {
    $address->streetName = ucwords(strtolower(preg_replace("!\s+!",' ',$address->streetName)));
    $address->community = ucwords(strtolower(preg_replace("!\s+!",' ',$address->community)));
    $address->parish = ucwords(strtolower(preg_replace("!\s+!",' ',$address->parish)));

    $data = array(
      'street_name' => $address->streetName,
      'community' => $address->community,
      'parish' => $address->parish,
    );

    $id = (int) $address->addressId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getAddress($id)) {
        $this->tableGateway->update($data, array('addr_id'=> $id));
      } else {
        throw new \Exception('Address id does not exist');
      }
    }
  }

  public function deleteAddress($id)
  {
    $this->tableGateway->delete(array('addr_id' => (int) $id));
  }
}
