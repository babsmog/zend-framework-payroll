<?php

namespace Payroll\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Payroll\Model\Pay;

class PayController extends AbstractActionController
{
  protected $payTable;
  protected $personnelTable;

  public function indexAction()
  {

    //Auto calculate current period based current date time
    $startDate = date_create(date('Y').'-01-01');
    $today = date_create(date('Y-m-d'));
    $periodDuration = 14;
    $difference = (date_diff($startDate,$today)->format("%a"));
    $currentPeriod = ((int)floor($difference/14))+1;
    $lastPeriod = $currentPeriod-1;

    return new ViewModel(array(
      'pays' => $this->getPayTable()->fetchAll($lastPeriod),
      'personnelTable' => $this->getPersonnelTable(),
    ));
  }


  public function deleteAction()
  {
    $id = (int) $this->params()->fromRoute('id',0);
    if (!$id) {
      return $this->redirect()->toRoute('pay');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del','No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getPayTable()->deletePay($id);
      }

      return $this->redirect()->toRoute('pay');
    }

    return array(
      'id' => $id,
      'pay' => $this->getPayTable()->getPay($id)
    );
  }

  public function getPayTable()
  {
    if (!$this->payTable) {
      $sm = $this->getServiceLocator();
      $this->payTable = $sm->get('Payroll\Model\PayTable');
    }
    return $this->payTable;
  }

  public function getPersonnelTable()
  {
    if (!$this->personnelTable) {
      $sm = $this->getServiceLocator();
      $this->personnelTable = $sm->get('Payroll\Model\PersonnelTable');
    }
    return $this->personnelTable;
  }
}
