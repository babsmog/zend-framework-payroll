<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Deduction;
use Payroll\Form\DeductionForm;

class DeductionController extends AbstractActionController
{
  protected $deductionTable;

  /*
  Corresponds to the index of the route.
  */
  public function indexAction()
  {
    /*
    With Zend Framework 2, in order to set variables in the view, we return a ViewModel instance where the first parameter
    of the constructor is an array from the action containing data we need. These are then automatically passed to the view
    script. The ViewModel object also allows us to change the view script that is used, but the default is to use
    {controller name}/{action name}. We can now fill in the index.phtml view script.
    */
    return new ViewModel(array(
      'deductions' => $this->getDeductionTable()->fetchAll(), // Passes deductions as an identifer into the index view.
    ));
  }

  /*
  Corresponds to the action:add of the route.
  */
  public function addAction()
  {
    $form = new DeductionForm();
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $deduction = new Deduction();
      $inputFilter = $deduction->getInputFilter();

      $form->setData($request->getPost());

      //////////////////////////////////////////////////////////////////////////////

      $value1 = $form->get('deduction_percentage')->getValue();
      $value2 = $form->get('fixed_amount')->getValue();
      /*
      $myfile = fopen("testfile.txt", "a");
      fwrite($myfile, $value1);
      fwrite($myfile, $value2);
      fwrite($myfile, 'HeartAche');
*/
      if (empty($value1) && !empty($value2)){
        $deduction->setFixedAmount();
      }

      if (!empty($value1) && empty($value2)){
        $deduction->setPercentage();
      }

      if (empty($value1) && empty($value2)){
        $deduction->setFixedAmount();
        $deduction->setPercentage();
      }

      if (!empty($value1) && !empty($value2)){
        $form->get('deduction_percentage')->setValue('');
        $form->get('fixed_amount')->setValue('');
        return array('form' => $form);
      }

      /////////////////////////////////////////////////////////////////////////////
      $form->setInputFilter($inputFilter);
      if ($form->isValid()) {
        $deduction->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.

        /*Checks to see if the deduction name already exist and if so redirects to the index of the deduction route. */
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $deductionName = $form->getData()['deduction_name'];
                $deductionRow = ($this->getDeductionTable()->getDeduction2($deductionName));
                if ($deductionRow) {
                  return $this->redirect()->toRoute('deduction');
                }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->getDeductionTable()->saveDeduction($deduction);

        return $this->redirect()->toRoute('deduction');
      }
    }
    return array('form' => $form);
  }

  /*
  Corresponds to the action:edit of the route.
  */
  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);
    /* If id from route/action/id is zero redirect to add form. */
    if (!$id) {
      return $this->redirect()->toRoute('deduction',array(
        'action' => 'add'
      ));
    }


    try {
      $deduction = $this->getDeductionTable()->getDeduction($id);
    }
    catch (\Exception $ex) {
      return $this->redirect()->toRoute('deduction',array(
        'action' => 'index'
      ));
    }


    $form = new DeductionForm();
    //The line below was not working as expected for some reason so I decided to do the extraction of the data manually.
    //$form->bind($deduction);

    $form->get('deduction_id')->setAttribute('value',$deduction->deductionId);
    $form->get('deduction_name')->setAttribute('value',$deduction->deductionName);
    $form->get('deduction_percentage')->setAttribute('value',$deduction->deductionPercentage);
    $form->get('fixed_amount')->setAttribute('value',$deduction->fixedAmount);
    $form->get('periodic_deduction')->setAttribute('value',$deduction->periodicDeduction);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($deduction->getInputFilter()); //Sets inputfilter from entity object.
      $form->setData($request->getPost());

      /* If form validates successfully then save changes. */
      if ($form->isValid()) {
        $deduction->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.
        $this->getDeductionTable()->saveDeduction($deduction);

        return $this->redirect()->toRoute('deduction',array(
          'action' => 'index'
        ));
      }
    }

    /* Pass these key value pairings as identifiers into the edit view. */
    return array(
      'id' => $id,
      'form' => $form,
    );

  }

  /*
  Corresponds to the action:delete of the route.
  */
  public function deleteAction()
  {
    /* If id from route/action/id is zero redirect to route index. */
    $id = (int) $this->params()->fromRoute('id',0);
    if (!$id) {
      return $this->redirect()->toRoute('deduction');
    }

    /* If request method is POST and 'del' is Yes delete row from database table. */
    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getDeductionTable()->deleteDeduction($id);
      }

      return $this->redirect()->toRoute('deduction');
    }

    /* Pass these key value pairings as identifiers into the delete view. */
    return array(
      'id' => $id,
      'deduction' => $this->getDeductionTable()->getDeduction($id)
    );
  }

  /*
  This method uses the services manager to get an instance of a Table object to
  access data in the database. This instance can be used throughout the Controller
  without creating new instances.
  */
  public function getDeductionTable()
  {
    if (!$this->deductionTable) {
      $sm = $this->getServiceLocator();
      $this->deductionTable = $sm->get('Payroll\Model\DeductionTable');
    }
    return $this->deductionTable;
  }

}
