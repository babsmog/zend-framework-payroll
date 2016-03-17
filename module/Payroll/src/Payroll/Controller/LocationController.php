<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Location;
use Payroll\Form\LocationForm;

class LocationController extends AbstractActionController
{
  protected $locationTable;

  public function indexAction()
  {
    return new ViewModel(array(
      'locations' => $this->getLocationTable()->fetchAll(),
    ));
  }

  public function addAction()
  {
    $form = new LocationForm();
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $location = new Location();
      $form->setInputFilter($location->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $location->exchangeArray($form->getData());
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $locationName = $form->getData()['location_name'];
                $locationRow = ($this->getLocationTable()->getLocation2($locationName));
                if ($locationRow) {
                  return $this->redirect()->toRoute('location');
                }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->getLocationTable()->saveLocation($location);

        return $this->redirect()->toRoute('location');
      }
    }
    return array('form' => $form);
  }

  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);

    if (!$id) {
      return $this->redirect()->toRoute('location',array(
        'action' => 'add'
      ));
    }

    try {
      $location = $this->getLocationTable()->getLocation($id);
    }
    catch (\Exception $ex) {
      return $this->redirect()->toRoute('location',array(
        'action' => 'index'
      ));
    }

    $form = new LocationForm();
    //$form->bind($location);
    $form->get('location_name')->setAttribute('value',$location->locationName);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($location->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $location->locationId = $id;
        $this->getLocationTable()->saveLocation($location);

        return $this->redirect()->toRoute('location',array(
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
      return $this->redirect()->toRoute('location');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getLocationTable()->deleteLocation($id);
      }

      return $this->redirect()->toRoute('location');
    }

    return array(
      'id' => $id,
      'location' => $this->getLocationTable()->getLocation($id)
    );
  }

  public function getLocationTable()
  {
    if (!$this->locationTable) {
      $sm = $this->getServiceLocator();
      $this->locationTable = $sm->get('Payroll\Model\LocationTable');
    }
    return $this->locationTable;
  }

}
