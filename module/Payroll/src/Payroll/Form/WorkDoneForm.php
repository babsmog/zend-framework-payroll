<?php

namespace Payroll\Form;

use Zend\Form\Form;

/*
Blueprint of the form object to be created.
*/
class WorkDoneForm extends Form
{
  protected $personnelTask_array = array();
  protected $location_array = array();

  public function __construct($name = null)
  {
    parent::__construct('work'); // Calls the parent constructor with a name as parameter.

    /* Adds an input field of type hidden to the form. */
    $this->add(array(
        'name' => 'work_id',
        'type' => 'Hidden',
    ));

    /* Adds a date input field. */
    $this->add(array(
      'name' => 'date_done',
      'type' => 'Text',
      'options' => array(
        'label' => 'Date Done ',
      ),
      'attributes' => array(
        'class' => 'form-control',
        'onchange' => 'workDoneDateValidator(this)',
      ),
    ));

    /* Adds an input field of type hidden to the form. */
    $this->add(array(
      'name' => 'hrs_worked',
      'type' => 'Text',
      'options' => array(
        'label' => 'Hours Worked ',
      ),
      'attributes' => array(
        'class' => 'form-control',
        'onchange' => 'workDoneValidator(this)',
      ),
    ));

    /* Adds a input field of type submit (submit button) to the form, and set a few familiar attributes. */
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

  /*
  This method is used to add a select input to the form just before it is presented to the user.
  This select input should have listed all personnel and task assignments from the database passed as parameter
  in this method.This is done to keep the logic for retrieving an array of personnel-task assignments from the database within the Table
  models and to avoid adding too much unnecessary code that is not form specific to this class.
  */
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

  /*
  This method is used to add a select input to the form just before it is presented to the user.
  This select input should have listed all locations from the database passed as parameter in this method.
  This is done to keep the logic for retrieving an array of locations from the database within the Table
  models and to avoid adding too much unnecessary code that is not form specific to this class.
  */
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
