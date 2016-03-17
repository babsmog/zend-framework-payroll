<?php

namespace Payroll\Model;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class PersonnelTask implements InputFilterAwareInterface
{
  public $personnelTaskId;
  public $personnelId;
  public $taskId;
  public $rate;
  protected $inputFilter;

  public function exchangeArray($data)
  {
    $this->personnelTaskId = (isset($data['personnel_task_id'])) ? $data['personnel_task_id'] : null;
    $this->personnelId = (isset($data['personnel_id'])) ? $data['personnel_id'] : null;
    $this->taskId = (isset($data['task_id'])) ? $data['task_id'] : null;
    $this->rate = (isset($data['rate'])) ? $data['rate'] : null;
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
        'name' => 'personnel_task_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'personnel_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'task_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'rate',
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

      $this->inputFilter = $inputFilter;
    }

    return $this->inputFilter;
  }
}
