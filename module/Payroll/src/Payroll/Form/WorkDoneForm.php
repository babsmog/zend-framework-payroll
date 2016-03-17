<?php

namespace Payroll\Form;

use Zend\Form\Form;

class WorkDoneForm extends Form
{
  protected $personnelTask_array = array();
  protected $location_array = array();

  public function __construct($name = null)
  {
    parent::__construct('work');

    $this->add(array(
        'name' => 'work_id',
        'type' => 'Hidden',
    ));


    $this->add(array(
      'name' => 'date_done',
      'type' => 'DateSelect',
      'options' => array(
        'label' => 'Date Completed ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    $this->add(array(
      'name' => 'hrs_worked',
      'type' => 'Text',
      'options' => array(
        'label' => 'Hours Worked ',
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


  public function setPersonnelTaskArray($array) {
    $this->personnelTask_array = $array;

    $this->add(array(
      'name' => 'personnel_task_id',
      'type' => 'Select',
      'attributes' => array(
        'class' => 'form-control',
        'style' => 'max-width:600px;',
        'id' => 'personnel_task_id',
      ),
      'options' => array(
        'label' => 'Personnel',
        'options' => $this->personnelTask_array,
      ),
    ));
  }

  public function setLocationArray($array) {
    $this->location_array = $array;

    $this->add(array(
      'name' => 'location_id',
      'type' => 'Select',
      'attributes' => array(
          'class' => 'form-control',
          'style' => 'max-width:300px;',
          'id' => 'location_id',
      ),
      'options' => array(
          'label' => 'Location',
          'options' => $this->location_array,
      ),
    ));
  }



}
