<?php

namespace Payroll\Model;

use Zend\Db\TableGateway\TableGateway;

class PayTable
{
  protected $tableGateway;

  public function __construct(TableGateway $tableGateway)
  {
    $this->tableGateway = $tableGateway;
  }

  public function fetchAll($period)
  {
    $period = (int) $period;
    $resultSet = $this->tableGateway->select(array('period' => $period));
    return $resultSet;
  }

  public function getPay($id)
  {
    $id = (int) $id;
    $rowset = $this->tableGateway->select(array('pay_id' => $id));
    $row = $rowset->current();
    if (!$row) {
      throw new \Exception("Could not find row $id");
    }
    return $row;
  }

  public function savePay(Pay $pay)
  {
    $data = array(
      'personnel_id' => $pay->personnelId,
      'amount' => (Float) $pay->amount,
      'period' => (int) $pay->period,
      'year' => (int) $pay->year,
    );

    $id = (int) $pay->payId;
    if ($id == 0) {
      $this->tableGateway->insert($data);
    } else {
      if ($this->getPay($id)) {
        $this->tableGateway->update($data, array('pay_id'=> $id));
      } else {
        throw new \Exception('Pay id does not exist');
      }
    }
  }

  public function deletePay($id)
  {
    $this->tableGateway->delete(array('pay_id' => (int) $id));
  }
}
