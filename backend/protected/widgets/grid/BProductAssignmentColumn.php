<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.widgets.grid
 */
class BProductAssignmentColumn extends BDataColumn
{
  protected function renderDataCellContent($row, $data)
  {
    $value = CHtml::value($data, $this->name);

    if( $value instanceof BActiveRecord && isset($value->name) )
    {
      echo $value->name;
    }
    elseif( is_array($value) )
    {
      echo implode(', ', array_map(function($item){
        return Arr::get($item, 'name');
      }, $value));
    }
    else
    {
      echo $this->grid->nullDisplay;
    }
  }

  protected function renderFilterDivContent()
  {
    if( is_string($this->filter) )
      echo $this->filter;
    else if( $this->filter !== false && $this->grid->filter !== null && $this->name !== null && strpos($this->name, '.') === false )
    {
      $relations = $this->grid->filter->relations();
      $relation  = isset($relations[$this->name]) ? $relations[$this->name][1] : null;

      if( $relation )
      {
        echo CHtml::activeLabel($this->grid->filter, $this->name.'_id', array('id' => false));
        echo CHtml::activeDropDownList($this->grid->filter, $this->name.'_id', $relation::listData(), array('id' => false, 'prompt' => ''));
      }
    }
  }
}