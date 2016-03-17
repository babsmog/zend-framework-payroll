<?php

namespace Payroll\Model;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Address implements InputFilterAwareInterface
{
  public $addressId;
  public $streetName;
  public $community;
  public $parish;
  protected $inputFilter;

  public function exchangeArray($data)
  {
    $this->addressId = (isset($data['addr_id'])) ? $data['addr_id'] : null;
    $this->streetName = (isset($data['street_name'])) ? $data['street_name'] : null;
    $this->community = (isset($data['community'])) ? $data['community'] : null;
    $this->parish = (isset($data['parish'])) ? $data['parish'] : null;
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
        'name' => 'addr_id',
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
