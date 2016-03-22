<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

/*
This Table class uses Zend Database TableGateway class to interact with our
database using an entity object. This is an implementation of the Table Data Gateway design pattern to allow for
interfacing with data in a database table. Be aware though that the Table Data Gateway pattern can become limiting in larger systems.
There is also a temptation to put database access code into controller action methods as these are exposed by
Zend\Db\TableGateway\AbstractTableGateway. Don’t do this!
*/
class AddressTable
{
  protected $tableGateway;

  public function __construct(TableGateway $tableGateway)
  {
    $this->tableGateway = $tableGateway; //Set the tableGateway object
  }


  /*
  Gets all the records from the entity table.
  and return it as a resultSet.
  */
  public function fetchAll()
  {
    $resultSet = $this->tableGateway->select();
    return $resultSet;
  }

  /*
  Creates a new entity object and saves it to the database.
  */
  public function createAddress($street_name,$community,$parish) {
    $address = new Address();
    $address->streetName = $street_name;
    $address->community = $community;
    $address->parish = $parish;
    $address->addressId = 0;
    $this->saveAddress($address);
  }

  /*
  Retrieves the record from the entity table with the specified id passed
  as parameter. If a record with the specified id cannot be found
  an Exception is thrown.
  */
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

  /*
  Retrieves records with the same column values as those in the parameters.
  If such a record(s) exists it is returned. If not null is returned.
  The idea behind this method is to find out if certain values already exist
  in a record and thus prevent them from being inserted again under a different
  primary key. This function also removes extra spaces and sets the entered values
  to lowercase before capitalizing each word.
  */
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

  /*
  Saves the passed entity object, but first removes any extra spaces and sets data
  to lowercase followed by capitalizing each word. Throws an exception if the id
  associated with the entity object cannot be found.
  */
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

  /*
  deletes an row in the entity table based on the id passed as parameter.
  */
  public function deleteAddress($id)
  {
    $this->tableGateway->delete(array('addr_id' => (int) $id));
  }
}
