<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Address;
use Payroll\Form\AddressForm;

class AddressController extends AbstractActionController
{
  protected $addressTable;

  public function indexAction()
  {
    return new ViewModel(array(
      'addresses' => $this->getAddressTable()->fetchAll(),
    ));
  }

  public function addAction()
  {
    $form = new AddressForm();
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $address = new Address();
      $form->setInputFilter($address->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $address->exchangeArray($form->getData());
        $this->getAddressTable()->saveAddress($address);

        return $this->redirect()->toRoute('address');
      }
    }
    return array('form' => $form);
  }

  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);

    if (!$id) {
      return $this->redirect()->toRoute('address',array(
        'action' => 'add'
      ));
    }

    try {
      $address = $this->getAddressTable()->getAddress($id);
    }
    catch (\Exception $ex) {
      return $this->redirect()->toRoute('address',array(
        'action' => 'index'
      ));
    }

    $form = new AddressForm();
    //$form->bind($address);
    $form->get('street_name')->setAttribute('value',$address->streetName);
    $form->get('community')->setAttribute('value',$address->community);
    $form->get('parish')->setAttribute('value',$address->parish);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($address->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $address->addressId = $id;
        $this->getAddressTable()->saveAddress($address);

        return $this->redirect()->toRoute('address',array(
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
      return $this->redirect()->toRoute('address');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getAddressTable()->deleteAddress($id);
      }

      return $this->redirect()->toRoute('address');
    }

    return array(
      'id' => $id,
      'address' => $this->getAddressTable()->getAddress($id)
    );
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
