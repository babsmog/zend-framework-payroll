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

  public function indexAction()
  {

    return new ViewModel(array(
      'personnels' => $this->getPersonnelTable()->fetchAll(),
      'addressTable' => $this->getAddressTable(),
    ));
  }

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
        $personnel->exchangeArray($form->getData());

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

        return $this->redirect()->toRoute('personnel');
      }
    }
    return array('form' => $form);
  }

  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);

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
      $form->setData($request->getPost());

      if ($form->isValid()) {

        $personnel->exchangeArray($form->getData());
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
        $personnel->personnelId = $id;



        $this->getPersonnelTable()->savePersonnel($personnel);

        return $this->redirect()->toRoute('personnel',array(
          'action' => 'index'
        ));
      }
    }

    return array(
      'id' => $id,
      'form' => $form,
    );

  }

  public function deleteAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);
    if (!$id) {
      return $this->redirect()->toRoute('personnel');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getPersonnelTable()->deletePersonnel($id);
      }

      return $this->redirect()->toRoute('personnel');
    }

    return array(
      'id' => $id,
      'personnel' => $this->getPersonnelTable()->getPersonnel($id)
    );
  }

  public function getPersonnelTable()
  {
    if (!$this->personnelTable) {
      $sm = $this->getServiceLocator();
      $this->personnelTable = $sm->get('Payroll\Model\PersonnelTable');
    }
    return $this->personnelTable;
  }

  public function getAddressTable()
  {
    if (!$this->addressTable) {
      $sm = $this->getServiceLocator();
      $this->addressTable = $sm->get('Payroll\Model\AddressTable');
    }
    return $this->addressTable;
  }

}
