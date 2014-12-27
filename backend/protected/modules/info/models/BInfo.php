<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.modules.info.models
 *
 * @method static BInfo model(string $class = __CLASS__)
 *
 * @property string  $id
 * @property string  $date
 * @property integer $position
 * @property string  $template
 * @property string  $name
 * @property string  $url
 * @property string  $notice
 * @property string  $content
 * @property string  $reference
 * @property string  $visible
 * @property string  $siblings
 * @property string  $children
 * @property string  $menu
 * @property string  $sitemap
 *
 * @mixin NestedSetBehavior
 */
class BInfo extends BAbstractMenuEntry
{
  const ROOT_ID = 1;

  public $parent;

  public function behaviors()
  {
    return array(
      'nestedSetBehavior' => array('class' => 'nestedset.NestedSetBehavior'),
      'uploadBehavior'    => array('class' => 'UploadBehavior', 'validAttributes' => "info_files")
    );
  }

  public function rules()
  {
    return array(
      array('name, url', 'required'),
      array('url', 'unique'),

      array('position', 'numerical', 'integerOnly' => true),
      array('template, url', 'length', 'max' => 255),
      array('visible, siblings, children, menu, sitemap', 'length', 'max' => 1),

      array('date', 'date', 'format' => 'dd.MM.yyyy'),
      array('notice, content, reference', 'safe'),
      array('parent', 'safe', 'on' => 'search'),
    );
  }

  public function attributeLabels()
  {
    return CMap::mergeArray(parent::attributeLabels(), array(
      'info_files' => 'Дополнительные файлы',
      'parent' => 'Родитель',
    ));
  }

  public function getImageTypes()
  {
    return array(
      'main' => 'Основное',
      'notice' => 'Анонс',
    );
  }

  public function getTreeActions()
  {
    return array(
      'add'      => array('title' => 'Добавить', 'url' => array('create' => array('parent' => '$data["id"]'))),
      'visible'  => array('title' => 'Видимость', 'class' => 'view'),
      'siblings' => 'Соседи',
      'children' => 'Потомки',
      'menu'     => 'В меню',
      'sitemap'  => 'На карте сайта',
      'delete'   => array('title' => 'Удалить', 'url' => array('delete' => array('id' => '$data["id"]'))),
    );
  }

  /**
   * Получаем всех родителей записи
   *
   * @param integer $id
   *
   * @return string
   */
  public function getStringPath($id = self::ROOT_ID)
  {
    $model   = self::model()->findByPk($id);
    $parents = $model->parent()->findAll();
    $path    = array($model->name);

    foreach($parents as $parent)
      $path[] = $parent->name;

    return '/ '.implode(" / ", array_reverse($path));
  }

  /**
   * @param CDbCriteria $criteria
   *
   * @return CDbCriteria
   */
  protected function getSearchCriteria(CDbCriteria $criteria)
  {
    $criteria->compare('visible', '='.$this->visible);
    $criteria->compare('name', $this->name, true);

    if( $this->parent )
    {
      $parent = $this->findByPk($this->parent);
      $criteria->addInCondition('id', CHtml::listData($parent->descendants()->findAll(), 'id', 'id'));
    }

    return $criteria;
  }

  protected function beforeSave()
  {
    if( parent::beforeSave() )
    {
      $this->date = $this->date ? date('Y-m-d', strtotime($this->date)) : null;
      return true;
    }

    return false;
  }

  protected function afterFind()
  {
    $this->date = $this->date ? date('d.m.Y', strtotime($this->date)) : '';
    parent::afterFind();
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getUrl()
  {
    return $this->url;
  }
}