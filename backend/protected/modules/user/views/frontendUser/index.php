<?php
/* @var $this UserController */
/* @var $dataProvider BActiveDataProvider */
/* @var $model BActiveRecord */
/* @var $searchDataProvider BActiveDataProvider */

Yii::app()->breadcrumbs->show();

$this->widget('BGridView', array(
  'filter' => $model,
  'dataProvider' => isset($searchDataProvider) ? $searchDataProvider : $model->search(),
  'columns'      => array(
    array('name' => 'id', 'class' => 'BPkColumn', 'ajaxUrl' => false, 'associationClass' => 'BUserAssociation'),
    array('name' => 'date_create', 'type' => 'datetime', 'value' => 'strtotime($data->date_create)', 'filter' => false),
    array('name' => 'login'),
    array('name' => 'fullName', 'value' => '$data->getFullName()', 'header' => 'Имя'),
    array('name' => 'email'),
    array('name' => 'userPhone', 'value' => '$data->profile ? $data->profile->phone : ""'),
    array('name' => 'ordersUrl', 'type' => 'html', 'filter' => false),
    array('name' => 'discount'),
    array('class' => 'JToggleColumn', 'name' => 'visible'),
    array('class' => 'BButtonUpdateColumn'),
  ),
));