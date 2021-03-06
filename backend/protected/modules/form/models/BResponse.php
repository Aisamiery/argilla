<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.modules.form.models
 * @method static BResponse model(string $class = __CLASS__)
 * @property string $id
 * @property string $product_id
 * @property string $name
 * @property string $email
 * @property string $content
 * @property integer $visible
 * @property BProduct $product
 */
class BResponse extends BActiveRecord
{
  public function behaviors()
  {
    return array(
      'dateFilterBehavior' => array(
        'class' => 'DateFilterBehavior',
        'attribute' => 'date',
      )
    );
  }

  public function rules()
  {
    return array(
      array('name', 'required'),
      array('visible', 'numerical', 'integerOnly' => true),
      array('product_id', 'length', 'max' => 10),
      array('name, email', 'length', 'max' => 255),
      array('content', 'safe'),
    );
  }

  public function relations()
  {
    return array(
      'product' => array(self::BELONGS_TO, 'BProduct', 'product_id'),
    );
  }

  public function attributeLabels()
  {
    return Cmap::mergeArray(parent::attributeLabels(), array(
      'name' => 'Имя'
    ));
  }

  /**
   * @param CDbCriteria $criteria
   *
   * @return CDbCriteria
   */
  protected function getSearchCriteria(CDbCriteria $criteria)
  {
    $criteria->compare('visible', '='.$this->visible);
    $criteria->compare('name', $this->email, true);

    return $criteria;
  }
}