<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>, Alexey Tatarinov <tatarinov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package frontend.models.product.filter
 */

class FilterElementRadio extends FilterElement
{
  public $itemClass = 'FilterElementItemRadio';

  /**
   * @return string
   */
  protected function getMergeType()
  {
    return self::MERGE_TYPE_SINGLE;
  }
}