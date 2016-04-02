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
This class represent the blueprint for each location object in our application.
It outlines the properties associated with a location as well as an input filter
used to validate data in the data array.
*/
class Deduction implements InputFilterAwareInterface
{
  public $deductionId;
  public $deductionName;
  public $deductionPercentage;
  public $fixedAmount;
  public $periodicDeduction;
  protected $inputFilter;

  /*
   In order to work with Zend\Db’s TableGateway class, we need to implement the exchangeArray() method.
   This method simply copies the data from the passed in array to our entity’s properties.
  */
  public function exchangeArray($data)
  {
    $this->deductionId = (!empty($data['deduction_id'])) ? $data['deduction_id'] : null;
    $this->deductionName = (!empty($data['deduction_name'])) ? $data['deduction_name'] : null;
    $this->deductionPercentage = (!empty($data['deduction_percentage'])) ? $data['deduction_percentage'] : null;
    $this->fixedAmount = (!empty($data['fixed_amount'])) ? $data['fixed_amount'] : null;
    $this->periodicDeduction = (!empty($data['periodic_deduction'])) ? $data['periodic_deduction'] : null;
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
        'name' => 'deduction_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'deduction_name',
        'required' => true,
        'filters' => array(
          array('name' => 'StripTags'),
          array('name' => 'StringTrim'),
        ),
        'validators' => array(
          array(
            'name' => 'Regex',
            'options' => array(
              'pattern' => '/^[A-Za-z]+(\s*|\s+[0-9]*|\s+[A-Za-z]*|[A-Za-z\s]*)$/',
            ),
          ),

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

  public function setPercentage(){
    $this->inputFilter->add(array(
      'name' => 'deduction_percentage',
      'filters' => array(
        array('name' => 'StripTags'),
        array('name' => 'StringTrim'),
      ),
      'validators' => array(

        array(
          'name' => 'Regex',
          'options' => array(
            'pattern' => '/^[0-9]+(.[0-9]+|[0-9]*)$/',
            'setMessage' => 'must be a decimal value from 0 to 100 [10, 1.0, 73.1]',
          ),
        ),

        array(
          'name' => 'LessThan',
          'options' => array(
            'max' => 100,
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

        array(
          'name' => 'NotEmpty',
          'options' => array(
            'setMessage' => 'both deduction percentage and fixed amount cannot be empty.',
          ),
        ),

      ),
    ));

  }

  public function setFixedAmount(){
    $this->inputFilter->add(array(
      'name' => 'fixed_amount',
      'required' => true,
      'filters' => array(
        array('name' => 'StripTags'),
        array('name' => 'StringTrim'),
      ),
      'validators' => array(

        array(
          'name' => 'Regex',
          'options' => array(
            'pattern' => '/^[0-9]+(.[0-9]+|[0-9]*)$/',
            'setMessage' => 'must be a decimal value from 0 to 9999999.99',
          ),
        ),

        array(
          'name' => 'LessThan',
          'options' => array(
            'max' => 9999999.99,
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

        array(
          'name' => 'NotEmpty',
          'options' => array(
            'setMessage' => 'both deduction percentage and fixed amount cannot be empty.',
          ),
        ),

      ),
    ));
  }
}
