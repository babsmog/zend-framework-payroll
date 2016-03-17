<?php

namespace Payroll\Model;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Task implements InputFilterAwareInterface
{
  public $taskId;
  public $taskName;
  protected $inputFilter;

  public function exchangeArray($data)
  {
    $this->taskId = (isset($data['task_id'])) ? $data['task_id'] : null;
    $this->taskName = (isset($data['task_name'])) ? $data['task_name'] : null;
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
  }
}
