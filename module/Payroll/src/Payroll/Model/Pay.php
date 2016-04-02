<?php

namespace Payroll\Model;

/*
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;*/

/*
This class represent the blueprint for each pay object in our application.
It outlines the properties associated with a record of pay as well as an input filter
used to validate data in the data array.
*/
class Pay
{
  public $payId;
  public $personnelId;
  public $grossAmount;
  public $netGross;
  public $period;
  public $year;
  protected $inputFilter;

  /*
   In order to work with Zend\Db’s TableGateway class, we need to implement the exchangeArray() method.
   This method simply copies the data from the passed in array to our entity’s properties.
  */
  public function exchangeArray($data)
  {
    $this->payId = (isset($data['pay_id'])) ? $data['pay_id'] : null;
    $this->personnelId = (isset($data['personnel_id'])) ? $data['personnel_id'] : null;
    $this->grossAmount = (isset($data['gross_amount'])) ? $data['gross_amount'] : null;
    $this->netAmount = (isset($data['net_amount'])) ? $data['net_amount'] : null;
    $this->period = (isset($data['period'])) ? $data['period'] : null;
    $this->year = (isset($data['year'])) ? $data['year'] : null;
  }

  /*
  Objects must implement either the exchangeArray() or populate() methods to support hydration
  (putting data array into class properties), and the getArrayCopy() method to support extraction
  (take data out of data array).
  */
  public function getArrayCopy()
  {
    return get_object_vars($this);
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
        'name' => 'task_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'task_name',
        'required' => true,
        'filters' => array(
          array('name' => 'StripTags'),
          array('name' => 'StringTrim'),
        ),
        'validators' => array(
          array(
            'name' => 'StringLength',
            'options' => array(
              'encoding' => 'UTF-8',
              'min' => 1,
              'max' => 100,
            ),
          ),
        ),
      ));

      $this->inputFilter = $inputFilter;
    }

    return $this->inputFilter;
  }*/
}
