<?php

namespace Payroll\Form;

use Zend\Form\Form;

/*
Blueprint of the form object to be created.
*/
class PersonnelTaskForm extends Form
{
  protected $personnel_array = array();
  protected $task_array = array();

  public function __construct($name = null)
  {
    parent::__construct('personnel_task'); // Calls the parent constructor with a name as parameter.

    /* Adds an input field of type hidden to the form. */
    $this->add(array(
        'name' => 'personnel_task_id',
        'type' => 'Hidden',
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'rate',
      'type' => 'Text',
      'options' => array(
        'label' => 'Rate ',
      ),
      'attributes' => array(
        'class' => 'form-control',
        'onchange' => 'rateValidator(this)',
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
  This select input should have listed all personnel from the database passed as parameter in this method.
  This is done to keep the logic for retrieving an array of personnels from the database within the Table
  models and to avoid adding too much unnecessary code that is not form specific to this class.
  */
  public function setPersonnelArray($array) {
    $this->personnel_array = $array;

    $this->add(array(
      'name' => 'personnel_id',
      'type' => 'Select',
      'attributes' => array(
        'class' => 'form-control',
        'style' => 'max-width:300px;',
        'id' => 'personnel_id',
      ),
      'options' => array(
        'label' => 'Personnel',
        'options' => $this->personnel_array,
      ),
    ));
  }

  /*
  This method is used to add a select input to the form just before it is presented to the user.
  This select input should have listed all task from the database passed as parameter in this method.
  This is done to keep the logic for retrieving an array of tasks from the database within the Table
  models and to avoid adding too much unnecessary code that is not form specific to this class.
  */
  public function setTaskArray($array) {
    $this->task_array = $array;

    $this->add(array(
      'name' => 'task_id',
      'type' => 'Select',
      'attributes' => array(
          'class' => 'form-control',
          'style' => 'max-width:300px;',
          'id' => 'task_id',
      ),
      'options' => array(
          'label' => 'Task',
          'options' => $this->task_array,
      ),
    ));
  }



}
