<?php
/**
 * @var FForm $form
 * @var UserController $this
 * @var array $_data_
 * @var Order[] $orders
 */
?>
<div id="content" class="wrapper">
  <?php $this->renderPartial('/_breadcrumbs');?>

  <div class="nofloat profile">
    <h1><?php echo Yii::app()->meta->setHeader('Личный кабинет')?></h1>

    <?php $this->renderPartial('_menu', $_data_) ?>

    <div class="profile-content">
      <h2>Текущие заказы</h2>
      <p class="s16 bb m25">Заказы, находящиеся в стадии обработки.</p>
      <?php if( $orders ) {?>
        <?php $this->widget('FListView', array(
          'dataProvider' => new FArrayDataProvider($orders, array('pagination' => false)),
          'itemView' => '_orders_block',
        ));?>
      <?php } else {?>
        Нет заказов.
      <?php }?>
    </div>
  </div>
</div>
