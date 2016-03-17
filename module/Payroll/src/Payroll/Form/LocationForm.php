<?php

namespace Payroll\Form;

use Zend\Form\Form;

class LocationForm extends Form
{
  public function __construct($name = null)
  {
    parent::__construct('location');

    $this->add(array(
        'name' => 'location_id',
        'type' => 'Hidden',
    ));

    $this->add(array(
      'name' => 'location_name',
      'type' => 'Text',
      'options' => array(
        'label' => 'Location Name ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    $this->add(array(
      'name' => 'submit',
      'type' => 'Submit',
      'attributes' => array(
        'value' => 'Go',
        'id' => 'submitbutton',
        'class' => 'btn btn-default',
      ),
    ));
  }
}
