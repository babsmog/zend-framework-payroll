<?php

namespace Payroll\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

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
/*
  public function setInputFilter(InputFilterInterface $inputFilter)
  {
    throw new \Exception("Not used");
  }

  public function getInputFilter()
  {
    if (!$this->inputFilter) {
      $inputFilter = new InputFilter();

      $inputFilter->add(array(
        'name' => 'pay_deduction_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'duration',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
        'validators' => array(
          array(
            'name' => 'Digits',
          ),

          array(
            'name' => 'GreaterThan',
            'options' => array(
              'min' => 1,
              'inclusive' => true,
            ),
          ),

        )
      ));

      $this->inputFilter = $inputFilter;
    }
    return $this->inputFilter;
  }*/

}
