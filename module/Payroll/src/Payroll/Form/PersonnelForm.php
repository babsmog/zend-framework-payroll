<?php

namespace Payroll\Form;

use Zend\Form\Form;

/*
Blueprint of the form object to be created.
*/
class PersonnelForm extends Form
{
  public function __construct($name = null)
  {
    parent::__construct('personnel'); // Calls the parent constructor with a name as parameter.

    /* Adds an input field of type hidden to the form. */
    $this->add(array(
        'name' => 'personnel_id',
        'type' => 'Hidden',
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'fname',
      'type' => 'Text',
      'options' => array(
        'label' => 'First Name ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'lname',
      'type' => 'Text',
      'options' => array(
        'label' => 'Last Name ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'age',
      'type' => 'Text',
      'options' => array(
        'label' => 'Age ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'street_name',
      'type' => 'Text',
      'options' => array(
        'label' => 'Street Name ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'community',
      'type' => 'Text',
      'options' => array(
        'label' => 'Community ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'parish',
      'type' => 'Text',
      'options' => array(
        'label' => 'Parish ',
      ),
      'attributes' => array(
        'class' => 'form-control',
      ),
    ));

    /* Adds a select input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'gender',
      'type' => 'Select',
      'attributes' => array(
        'class' => 'form-control',
        'style' => 'max-width:100px;',
        'id' => 'gender',
      ),
      'options' => array(
        'label' => 'Gender',
        'options' => array(
          'M' => 'Male',
          'F' => 'Female',
        ),
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
