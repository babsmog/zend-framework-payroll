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
This class represent the blueprint for each work done object in our application.
It outlines the properties associated with the work done as well as an input filter
used to validate data in the data array.
*/
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

  /*
   In order to work with Zend\Db’s TableGateway class, we need to implement the exchangeArray() method.
   This method simply copies the data from the passed in array to our entity’s properties.
  */
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
        'validators' => array(
          array(
            'name' => 'Date',
            'options' => array(
            )
          ),

        ),
      ));

      $inputFilter->add(array(
        'name' => 'hrs_worked',
        'required' => true,
        'validators' => array(
          array(
            'name' => 'Regex',
            'options' => array(
              'pattern' => '/^[0-9]+(.[0-9]+|[0-9]*)$/',
              'setMessage' => 'Should be a valid decimal value.',
            ),
          ),

          array(
            'name' => 'LessThan',
            'options' => array(
              'max' => 999.99,
              'inclusive' => true,
            ),
          ),

          array(
            'name' => 'GreaterThan',
            'options' => array(
              'min' => 0,
              'inclusive' => true,
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
