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
class PayDetailsTable
{
  protected $tableGateway;

  public function __construct(TableGateway $tableGateway)
  {
    $this->tableGateway = $tableGateway; //Set the tableGateway object
  }

  /*
  Gets all the records from the entity table.
  and return it as a resultSet.
  *//*
  public function fetchAll($personnelId)
  {
    $period = (int) $period;
    $resultSet = $this->tableGateway->select(array('personnel_id' => $personnelId));
    return $resultSet;
  }*/

  /*
  Retrieves the record from the entity table with the specified id passed
  as parameter. If a record with the specified id cannot be found
  an Exception is thrown.
  */
  public function getPayDetails($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('pay_details_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }


  public function createPayDetails(PayDetails $payDetails)
  {
    $data = array(
      'pay_details_id' => $payDetails->payDetailsId,
      'personnel_id' => (int) $payDetails->personnelId,
      'gross_amount' => (Float) $payDetails->grossAmount,
      'net_amount' => (Float) $payDetails->netAmount,
      'period' => (int) $payDetails->period,
      'year' => (int) $payDetails->year,
      'description_of_work' => $payDetails->descriptionOfWork,
      'applied_deductions' => $payDetails->appliedDeductions,
    );
    $this->tableGateway->insert($data);
  }

  /*
  Saves the passed entity object. Throws an exception if the id
  associated with the entity object cannot be found.
  */
  public function savePayDetails(PayDetails $payDetails)
  {
    $data = array(
      'pay_details_id' => $payDetails->payDetailsId,
      'personnel_id' => $payDetails->personnelId,
      'gross_amount' => (Float) $payDetails->grossAmount,
      'net_amount' => (Float) $payDetails->netAmount,
      'period' => (int) $payDetails->period,
      'year' => (int) $payDetails->year,
      'description_of_work' => $payDetails->descriptionOfWork,
      'applied_deductions' =>  $payDetails->appliedDeductions,
    );
    if ($payDetails->payDetailsId) {
      $this->tableGateway->update($data, array('pay_details_id'=> $payDetails->payDetailsId));
    } else {
      throw new \Exception('PayDetails id does not exist');
    }
  }

  /*
  deletes an row in the entity table based on the id passed as parameter.
  */
  public function deletePayDetails($id)
  {
    $this->tableGateway->delete(array('pay_details_id' => (int) $id));
  }
}
