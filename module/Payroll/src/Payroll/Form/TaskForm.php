<?php

namespace Payroll\Form;

use Zend\Form\Form;

class TaskForm extends Form
{
  public function __construct($name = null)
  {
    parent::__construct('task');

    $this->add(array(
        'name' => 'task_id',
        'type' => 'Hidden',
    ));

    $this->add(array(
      'name' => 'task_name',
      'type' => 'Text',
      'options' => array(
        'label' => 'Task Name ',
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
