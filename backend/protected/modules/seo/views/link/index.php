<?php
/**
 * @var BLinkController $this
 * @var BLink $model
 * @var BActiveDataProvider $dataProvider
*/

Yii::app()->breadcrumbs->show();

$this->widget('BGridView', array(
  'filter' => $model,
  'dataProvider' => $dataProvider,
  'columns' => array(
    array('name' => 'id', 'htmlOptions' => array('class' => 'center span1'), 'filter' => false),
    array('name' => 'position', 'htmlOptions' => array('class' => 'span1'), 'class' => 'OnFlyEditField', 'filter' => false),
    array('name' => 'page', 'htmlOptions' => array('class' => 'center span1')),
    array('name' => 'url'),
    array('name' => 'section_id', 'value' => '$data->section->name', 'filter' => CHtml::listData(BLinkSection::model()->findAll(), 'id', 'name')),

    array('class' => 'JToggleColumn', 'name' => 'visible'),
    array('class' => 'BButtonColumn'),
  ),
));