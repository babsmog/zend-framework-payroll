<?php

namespace Payroll\Form;

use Zend\Form\Form;

/*
Blueprint of the form object to be created.
*/
class DeductionForm extends Form
{
  public function __construct($name = null)
  {
    parent::__construct('deduction'); // Calls the parent constructor with a name as parameter.

    /* Adds an input field of type hidden to the form. */
    $this->add(array(
        'name' => 'deduction_id',
        'type' => 'Hidden',
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'deduction_name',
      'type' => 'Text',
      'options' => array(
        'label' => 'Deduction Name ',
      ),
      'attributes' => array(
        'class' => 'form-control',
        'onchange' => 'deductionValidateName(this)',
      ),
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'deduction_percentage',
      'type' => 'Text',
      'options' => array(
        'label' => 'Deduction Percentage ',
      ),
      'attributes' => array(
        'class' => 'form-control',
        'onchange' => 'deductionValidatePercentage(this)',
      ),
    ));

    /* Adds a text input field with some attributes such as the class set. */
    $this->add(array(
      'name' => 'fixed_amount',
      'type' => 'Text',
      'options' => array(
        'label' => 'Fixed Amount ',
      ),
      'attributes' => array(
        'class' => 'form-control',
        'onchange' => 'deductionValidateAmount(this)',
      ),
    ));

    $this->add(array(
        'type' => 'Radio',
        'name' => 'periodic_deduction',
        'options' => array(
            'label' => 'Should this tax deduction be applied to all employees every period ?',
            'value_options' => array(
                '0' => 'No',
                '1' => 'Yes',
            ),
        'attributes' => array(
          'class' => 'form-control',
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
