<?php

namespace Payroll\Model;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class WorkDone implements InputFilterAwareInterface
{
  public $workId;
  public $personnelTaskId;
  public $dateDone;
  public $period;
  public $hoursWorked;
  public $locationId;
  public $year;
  protected $inputFilter;

  public function exchangeArray($data)
  {
    $this->workId = (isset($data['work_id'])) ? $data['work_id'] : null;
    $this->personnelTaskId = (isset($data['personnel_task_id'])) ? $data['personnel_task_id'] : null;
    $this->dateDone = (isset($data['date_done'])) ? $data['date_done'] : null;
    $this->period = (isset($data['period'])) ? $data['period'] : null;
    $this->hoursWorked = (isset($data['hrs_worked'])) ? $data['hrs_worked'] : null;
    $this->locationId = (isset($data['location_id'])) ? $data['location_id'] : null;
    $this->year = (isset($data['year'])) ? $data['year'] : null;
  }

  public function getArrayCopy()
  {
    return get_object_vars($this);
  }

  public function setInputFilter(InputFilterInterface $inputFilter)
  {
    throw new \Exception("Not used");
  }

  public function getInputFilter()
  {
    if (!$this->inputFilter) {
      $inputFilter = new InputFilter();

      $inputFilter->add(array(
        'name' => 'work_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'personnel_task_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));


      $inputFilter->add(array(
        'name' => 'date_done',
        'required' => true,
        'filters' => array(
          array('name' => 'StripTags'),
          array('name' => 'StringTrim'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'hrs_worked',
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
              'max' => 7,
            ),
          ),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'location_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $this->inputFilter = $inputFilter;
    }

    return $this->inputFilter;
  }
}
