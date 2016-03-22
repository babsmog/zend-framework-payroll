<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Personnel;
use Payroll\Form\PersonnelForm;

class PersonnelController extends AbstractActionController
{
  protected $personnelTable;
  protected $addressTable;

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
      'personnels' => $this->getPersonnelTable()->fetchAll(),
      'addressTable' => $this->getAddressTable(),
    ));
  }

  /*
  Corresponds to the action:add of the route.
  */
  public function addAction()
  {
    $form = new PersonnelForm();
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $personnel = new Personnel();
      $form->setInputFilter($personnel->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $personnel->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.

/*This block of code looks up the address entered for this personnel in the address table if it already exists then
the address id of the referenced address is set to the id of the already existing address. If it doesn't
then a new address entry is made to the address table and its id is used to set the referenced key.
*/
///////////////////////////////////////////////////////////////////////////////////////////////////////
        $street_name = $form->getData()['street_name'];
        $community = $form->getData()['community'];
        $parish = $form->getData()['parish'];
        $addressRow = ($this->getAddressTable()->getAddress2($street_name,$community,$parish));
        if (!$addressRow) {
          $address = 0; // reference Id is set to zero so as to create a new address when saveAddress method from Table class.
        }
        else {
          $address = $addressRow->addressId; // if it already exists reference Id is set to the id of the found row.
        }

        if (!$address){
          $this->getAddressTable()->createAddress($street_name,$community,$parish);
          $addressRow = ($this->getAddressTable()->getAddress2($street_name,$community,$parish));
          $address = $addressRow->addressId;
        }

        $personnel->address = $address;
////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->getPersonnelTable()->savePersonnel($personnel);

        return $this->redirect()->toRoute('personnel');
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
      return $this->redirect()->toRoute('personnel',array(
        'action' => 'add'
      ));
    }

    try {
      $personnel = $this->getPersonnelTable()->getPersonnel($id);
      $address = $this->getAddressTable()->getAddress($personnel->address);
    }
    catch (\Exception $ex) {
      return $this->redirect()->toRoute('personnel',array(
        'action' => 'index'
      ));
    }

    $form = new PersonnelForm();
    /* Loading the data array values into the form manually */
    $form->get('personnel_id')->setAttribute('value',$personnel->personnelId);
    $form->get('fname')->setAttribute('value',$personnel->fName);
    $form->get('lname')->setAttribute('value',$personnel->lName);
    $form->get('age')->setAttribute('value',$personnel->age);
    $form->get('street_name')->setAttribute('value',$address->streetName);
    $form->get('community')->setAttribute('value',$address->community);
    $form->get('parish')->setAttribute('value',$address->parish);
    $form->get('gender')->setAttribute('value',$personnel->gender);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($personnel->getInputFilter());
      $form->setData($request->getPost()); // Set data from post request.

      if ($form->isValid()) {

        $personnel->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.

        /*This block of code looks up the address entered for this personnel in the address table if it already exists then
        the address id of the referenced address is set to the id of the already existing address. If it doesn't
        then a new address entry is made to the address table and its id is used to set the referenced key.
        */
        ///////////////////////////////////////////////////////////////////////////////////////////////////////

                $street_name = $form->getData()['street_name'];
                $community = $form->getData()['community'];
                $parish = $form->getData()['parish'];
                $addressRow = ($this->getAddressTable()->getAddress2($street_name,$community,$parish));
                if (!$addressRow) {
                  $address = 0;
                }
                else {
                  $address = $addressRow->addressId;
                }

                if (!$address){
                  $this->getAddressTable()->createAddress($street_name,$community,$parish);
                  $addressRow = ($this->getAddressTable()->getAddress2($street_name,$community,$parish));
                  $address = $addressRow->addressId;
                }

                $personnel->address = $address;

        ////////////////////////////////////////////////////////////////////////////////////////////////////////



        $this->getPersonnelTable()->savePersonnel($personnel);

        return $this->redirect()->toRoute('personnel',array(
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
      return $this->redirect()->toRoute('personnel');
    }

    $request = $this->getRequest();
    /* If request method is POST and 'del' is Yes delete row from database table. */
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getPersonnelTable()->deletePersonnel($id);
      }

      return $this->redirect()->toRoute('personnel');
    }

    /* Pass these key value pairings as identifiers into the delete view. */
    return array(
      'id' => $id,
      'personnel' => $this->getPersonnelTable()->getPersonnel($id)
    );
  }

  /*
  This method uses the services manager to get an instance of a Table object to
  access data in the database. This instance can be used throughout the Controller
  without creating new instances.
  */
  public function getPersonnelTable()
  {
    if (!$this->personnelTable) {
      $sm = $this->getServiceLocator();
      $this->personnelTable = $sm->get('Payroll\Model\PersonnelTable');
    }
    return $this->personnelTable;
  }

  /*
  This method uses the services manager to get an instance of a Table object to
  access data in the database. This instance can be used throughout the Controller
  without creating new instances.
  */
  public function getAddressTable()
  {
    if (!$this->addressTable) {
      $sm = $this->getServiceLocator();
      $this->addressTable = $sm->get('Payroll\Model\AddressTable');
    }
    return $this->addressTable;
  }

}
