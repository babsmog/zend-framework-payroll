<?php

namespace Payroll\Form;

use Zend\Form\Form;

class PersonnelTaskForm extends Form
{
  protected $personnel_array = array();
  protected $task_array = array();

  public function __construct($name = null)
  {
    parent::__construct('personnel_task');

    $this->add(array(
        'name' => 'personnel_task_id',
        'type' => 'Hidden',
    ));


    $this->add(array(
      'name' => 'rate',
      'type' => 'Text',
      'options' => array(
        'label' => 'Rate ',
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
