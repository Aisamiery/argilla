<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.modules.order.models
 *
 * @method static BOrderStatus model(string $class = __CLASS__)
 *
 * @property integer $id
 * @property string $name
 * @property string $sysname
 */
class BOrderStatus extends BActiveRecord
{
  const STATUS_NEW = 1;

  const STATUS_CONFIRMED = 2;

  const STATUS_WAIT_PAYMENT = 3;

  const STATUS_PAID = 4;

  const STATUS_DELIVERED = 5;

  const STATUS_CANCELED = 6;

  private $platronStatus = array(
    'error'   => 'Ошибка',
    'pending' => 'В ожидании',
    'ok'      => 'Оплачен',
    'failed'  => 'Отказ',
  );

  public function getPlatronStatus($status)
  {
    return $this->platronStatus[$status];
  }

  public function getStatusesByPayment($payment)
  {
    $criteria = new CDbCriteria();

    switch($payment)
    {
      case BOrderPaymentType::CASH:
        $criteria->addNotInCondition('id', array(
          self::STATUS_WAIT_PAYMENT,
          self::STATUS_PAID
        ));
      break;

      case BOrderPaymentType::EPAY:
        $criteria->addNotInCondition('id', array(self::STATUS_WAIT_PAYMENT));
      break;
    }

    $statuses = $this->findAll($criteria);

    return $statuses;
  }
}