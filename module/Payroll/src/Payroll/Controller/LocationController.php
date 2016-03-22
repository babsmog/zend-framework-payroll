<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Location;
use Payroll\Form\LocationForm;

class LocationController extends AbstractActionController
{
  protected $locationTable;

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
      'locations' => $this->getLocationTable()->fetchAll(), // Passes locations as an identifer into the index view.
    ));
  }

  /*
  Corresponds to the action:add of the route.
  */
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
        $location->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.

        /*Checks to see if the location name already exist and if so redirects to the index of the location route. */
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

  /*
  Corresponds to the action:edit of the route.
  */
  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);
    /* If id from route/action/id is zero redirect to add form. */
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
    //The line below was not working as expected for some reason so I decided to do the extraction of the data manually.
    //$form->bind($location);

    $form->get('location_id')->setAttribute('value',$location->locationId);
    $form->get('location_name')->setAttribute('value',$location->locationName);
    $form->get('submit')->setAttribute('value','Save');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($location->getInputFilter()); //Sets inputfilter from entity object.
      $form->setData($request->getPost());

      /* If form validates successfully then save changes. */
      if ($form->isValid()) {
        $location->exchangeArray($form->getData()); // Puts form data into the data array in the corresponding model.
        $this->getLocationTable()->saveLocation($location);

        return $this->redirect()->toRoute('location',array(
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
      return $this->redirect()->toRoute('location');
    }

    /* If request method is POST and 'del' is Yes delete row from database table. */
    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getLocationTable()->deleteLocation($id);
      }

      return $this->redirect()->toRoute('location');
    }

    /* Pass these key value pairings as identifiers into the delete view. */
    return array(
      'id' => $id,
      'location' => $this->getLocationTable()->getLocation($id)
    );
  }

  /*
  This method uses the services manager to get an instance of a Table object to
  access data in the database. This instance can be used throughout the Controller
  without creating new instances.
  */
  public function getLocationTable()
  {
    if (!$this->locationTable) {
      $sm = $this->getServiceLocator();
      $this->locationTable = $sm->get('Payroll\Model\LocationTable');
    }
    return $this->locationTable;
  }

}
