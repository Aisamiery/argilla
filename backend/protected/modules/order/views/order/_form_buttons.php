<?php
/**
 * @var BController $this
 * @var BActiveRecord $model
 */
?>
<div class="s-buttons s-buttons-additional">

  <?php if( $this->isUpdate() ) {?>
    <?php $this->widget('BButton', array(
      'label' => 'Печать',
      'popupDepended' => false,
      'disabled' => $model->isNewRecord,
      'url' => Yii::app()->controller->createUrl("/order/bOrder/print", array(
          "id" => $model->id
        )),
      'htmlOptions' => array(
        'target' => '_blank',
        'class' => 'btn-print'
      ),
    )); ?>
  <?php }?>

  <?php $this->widget('BButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? 'Создать' : 'Применить',
    'popupDepended' => false,
  )); ?>

  <?php $this->widget('BButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => 'Сохранить',
    'htmlOptions' => array('name' => 'action', 'value' => 'index'),
    'popupDepended' => true,
  )); ?>

  <?php $this->widget('BButton', array(
    'type' => 'danger',
    'label' => 'Закрыть',
    'url' => $this->getBackUrl(),
    'popupDepended' => true,
  )); ?>
</div>