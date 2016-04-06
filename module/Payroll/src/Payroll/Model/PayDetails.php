<?php

namespace Payroll\Model;

/*
This class represent the blueprint for each pay object in our application.
It outlines the properties associated with a record of pay as well as an input filter
used to validate data in the data array.
*/
class PayDetails
{
  public $payDetailsId;
  public $personnelId;
  public $grossAmount;
  public $netAmount;
  public $period;
  public $year;
  public $descriptionOfWork;
  public $appliedDeductions;
  protected $inputFilter;

  /*
   In order to work with Zend\Db’s TableGateway class, we need to implement the exchangeArray() method.
   This method simply copies the data from the passed in array to our entity’s properties.
  */
  public function exchangeArray($data)
  {
    $this->payDetailsId = (isset($data['pay_details_id'])) ? $data['pay_details_id'] : null;
    $this->personnelId = (isset($data['personnel_id'])) ? $data['personnel_id'] : null;
    $this->grossAmount = (isset($data['gross_amount'])) ? $data['gross_amount'] : null;
    $this->netAmount = (isset($data['net_amount'])) ? $data['net_amount'] : null;
    $this->period = (isset($data['period'])) ? $data['period'] : null;
    $this->year = (isset($data['year'])) ? $data['year'] : null;
    $this->descriptionOfWork = (isset($data['description_of_work'])) ? $data['description_of_work'] : null;
    $this->appliedDeductions = (isset($data['applied_deductions'])) ? $data['applied_deductions'] : null;
  }
}
