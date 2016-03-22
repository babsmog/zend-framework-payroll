<?php

namespace Payroll\Model;

/*
Classes imported via namespace to implement an InputFilter
for our model.
*/
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/*
This class represent the blueprint for each personnel and task assignment object in our application.
It outlines the properties associated with an assignment as well as an input filter
used to validate data in the data array.
*/
class PersonnelTask implements InputFilterAwareInterface
{
  public $personnelTaskId;
  public $personnelId;
  public $taskId;
  public $rate;
  protected $inputFilter;

  /*
   In order to work with Zend\Db’s TableGateway class, we need to implement the exchangeArray() method.
   This method simply copies the data from the passed in array to our entity’s properties.
  */
  public function exchangeArray($data)
  {
    $this->personnelTaskId = (isset($data['personnel_task_id'])) ? $data['personnel_task_id'] : null;
    $this->personnelId = (isset($data['personnel_id'])) ? $data['personnel_id'] : null;
    $this->taskId = (isset($data['task_id'])) ? $data['task_id'] : null;
    $this->rate = (isset($data['rate'])) ? $data['rate'] : null;
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
  Though we don't actually use this method it must be implemented by virtue of the interface-->InputFilterAwareInterface.
  */
  public function setInputFilter(InputFilterInterface $inputFilter)
  {
    throw new \Exception("Not used");
  }

  /*
  Sets up our method to filter the data from the forms that will be stored in our
  models.
  */
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
