<?php

namespace Payroll\Model;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Personnel implements InputFilterAwareInterface
{
  public $personnelId;
  public $fName;
  public $lName;
  public $age;
  public $address;
  public $gender;
  protected $inputFilter;

  public function exchangeArray($data)
  {
    $this->personnelId = (isset($data['personnel_id'])) ? $data['personnel_id'] : null;
    $this->fName = (isset($data['fname'])) ? $data['fname'] : null;
    $this->lName = (isset($data['lname'])) ? $data['lname'] : null;
    $this->age = (isset($data['age'])) ? $data['age'] : null;
    $this->address = (isset($data['address'])) ? $data['address'] : null;
    $this->gender = (isset($data['gender'])) ? $data['gender'] : null;
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
        'name' => 'personnel_id',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'fname',
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

      $inputFilter->add(array(
        'name' => 'lname',
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

      $inputFilter->add(array(
        'name' => 'age',
        'required' => true,
        'filters' => array(
          array('name' => 'Int'),
        ),
      ));

      $inputFilter->add(array(
        'name' => 'street_name',
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

      $inputFilter->add(array(
        'name' => 'community',
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

      $inputFilter->add(array(
        'name' => 'parish',
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
