<?php

namespace Payroll\Form;

use Zend\Form\Form;

/*
Blueprint of the form object to be created.
*/
class TaskForm extends Form
{
  public function __construct($name = null)
  {
    parent::__construct('task'); // Calls the parent constructor with a name as parameter.

    /* Adds an input field of type hidden to the form. */
    $this->add(array(
        'name' => 'task_id',
        'type' => 'Hidden',
    ));

    /* Adds a text input field with some attributes such as the class set. */
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
}
