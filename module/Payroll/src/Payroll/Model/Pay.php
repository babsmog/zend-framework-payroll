<?php

namespace Payroll\Model;

/*
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;*/


class Pay
{
  public $payId;
  public $personnelId;
  public $amount;
  public $period;
  public $year;
  protected $inputFilter;

  public function exchangeArray($data)
  {
    $this->payId = (isset($data['pay_id'])) ? $data['pay_id'] : null;
    $this->personnelId = (isset($data['personnel_id'])) ? $data['personnel_id'] : null;
    $this->amount = (isset($data['amount'])) ? $data['amount'] : null;
    $this->period = (isset($data['period'])) ? $data['period'] : null;
    $this->year = (isset($data['year'])) ? $data['year'] : null;
  }

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
