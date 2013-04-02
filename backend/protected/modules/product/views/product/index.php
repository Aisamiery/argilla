<?php
/* @var BProductController $this */
/* @var BActiveDataProvider $dataProvider */
/* @var BProduct $model */

Yii::app()->breadcrumbs->show();

$this->widget('BGridView', array(
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
    array('class' => 'BPkColumn'),
    array('name' => 'position', 'class' => 'OnFlyEditField', 'htmlOptions' => array('class' => 'span1'), 'header' => 'Позиция', 'filter' => false),

    array(
      'name' => 'name',
      'value' => 'CHtml::link($data->name, Yii::app()->controller->createUrl("update", array("id" => $data->id)))',
      'type' => 'html',
    ),

    array(
      'value' => 'BProductSection::model()->findByPk($data->section->id)->name',
      'name' => 'section_id',
      'filter' => CHtml::listData(BProductSection::model()->findAll(), 'id', 'name')
    ),

    array(
      'value' => '$data->type ? BProductType::model()->findByPk($data->type->id)->name : ""',
      'name' => 'type_id',
      'filter' => CHtml::listData(BProductType::model()->findAll(), 'id', 'name')
    ),

    array('name' => 'price', 'class' => 'OnFlyEditField', 'htmlOptions' => array('class' => 'span1'), 'header' => 'Цена', 'filter' => false),

    array(
      'name' => 'BProduct',
      'header' => 'Продукты',
      'class' => 'BAssociationColumn',
      'iframeAction' => 'index',
    ),

    array('class' => 'JToggleColumn', 'name' => 'visible', 'filter' => CHtml::listData($model->yesNoList(), 'id', 'name')),
    array('class' => 'BButtonColumn'),
  ),
));