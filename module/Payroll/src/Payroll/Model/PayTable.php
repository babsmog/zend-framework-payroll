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
class PayTable
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
  public function fetchAll($period)
  {
    $period = (int) $period;
    $resultSet = $this->tableGateway->select(array('period' => $period));
    return $resultSet;
  }

  /*
  Retrieves the record from the entity table with the specified id passed
  as parameter. If a record with the specified id cannot be found
  an Exception is thrown.
  */
  public function getPay($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('pay_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  /*
  Saves the passed entity object, but first removes any extra spaces and sets data
  to lowercase followed by capitalizing each word. Throws an exception if the id
  associated with the entity object cannot be found.
  */
  public function savePay(Pay $pay)
  {
    $data = array(
      'personnel_id' => $pay->personnelId,
      'amount' => (Float) $pay->amount,
      'period' => (int) $pay->period,
      'year' => (int) $pay->year,
    );

    $id = (int) $pay->payId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getPay($id)) {
        $this->tableGateway->update($data, array('pay_id'=> $id));
      } else {
        throw new \Exception('Pay id does not exist');
      }
    }
  }

  /*
  deletes an row in the entity table based on the id passed as parameter.
  */
  public function deletePay($id)
  {
    $this->tableGateway->delete(array('pay_id' => (int) $id));
  }
}
