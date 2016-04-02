<?php

namespace Payroll\Model;

/*
This class represent the blueprint for each payDeduction object in our application.
It outlines the properties associated with a payDeduction as well as an input filter
used to validate data in the data array.
*/
class PayDeduction
{
  public $payDeductionId;
  public $deductionId;
  public $personnelId;
  public $duration;
  protected $inputFilter;

  /*
   In order to work with Zend\Db’s TableGateway class, we need to implement the exchangeArray() method.
   This method simply copies the data from the passed in array to our entity’s properties.
  */
  public function exchangeArray($data)
  {
    $this->payDeductionId = (!empty($data['pay_deduction_id'])) ? $data['pay_deduction_id'] : null;
    $this->deductionId = (!empty($data['deduction_id'])) ? $data['deduction_id'] : null;
    $this->personnelId = (!empty($data['personnel_id'])) ? $data['personnel_id'] : null;
    $this->duration = (!empty($data['duration'])) ? $data['duration'] : null;
  }

}
