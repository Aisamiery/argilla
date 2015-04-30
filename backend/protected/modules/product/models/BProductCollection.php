<?php
/**
 * @author Alexey Tatarinov <tatarinov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.modules.product.models
 *
 * @method static BProductCollection model(string $class = __CLASS__)
 *
 * @property integer $id
 * @property integer $position
 * @property string $name
 * @property string $url
 * @property string $img
 * @property string $notice
 * @property integer $visible
 *
 * @mixin BTreeAssignmentBehavior
 */
class BProductCollection extends BProductStructure
{
  public function behaviors()
  {
    return array(
      'uploadBehavior' => array('class' => 'UploadBehavior', 'validAttributes' => 'img'),
      'tree' => array('class' => 'BTreeAssignmentBehavior', 'parentModel' => 'BProductCategory'),
    );
  }

  public function rules()
  {
    return array(
      array('url, name', 'required'),
      array('url', 'unique'),
      array('position, visible', 'numerical', 'integerOnly' => true),
      array('url', 'length', 'max' => 255),
      array('name, notice', 'safe'),
    );
  }

  public function attributeLabels()
  {
    return CMap::mergeArray(parent::attributeLabels(), array(
      'parent_id' => 'Категория',
    ));
  }

  protected function getSearchCriteria(CDbCriteria $criteria)
  {
    $criteria->compare('t.visible', $this->visible);
    $criteria->compare('t.name', $this->name, true);

    return $criteria;
  }
}