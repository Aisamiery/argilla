<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package frontend.models.product
 *
 * @method static ProductAssignment model(string $class = __CLASS__) *
 *
 * @property int $product_id
 * @property int $section_id
 * @property int $type_id
 * @property int $category_id
 * @property int $collection_id
 */
class ProductAssignment extends FActiveRecord
{
  protected $assignments = array();

  public function relations()
  {
    return array(
      'product' => array(self::BELONGS_TO, 'ProductProduct', 'product_id'),
      'section' => array(self::BELONGS_TO, 'ProductSection', 'section_id'),
      'type' => array(self::BELONGS_TO, 'ProductType', 'type_id'),
      'category' => array(self::BELONGS_TO, 'ProductCategory', 'category_id'),
      'collection' => array(self::BELONGS_TO, 'ProductCollection', 'collection_id'),
    );
  }

  /**
   * @param CDbCriteria $defaultCriteria
   *
   * @return array
   */
  public function getAssignments(CDbCriteria $defaultCriteria = null)
  {
    $criteria = $this->buildCriteria($defaultCriteria);
    $key = md5(serialize($criteria->toArray()));

    if( !isset($this->assignments[$key]) )
    {
      $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
      $command = $builder->createFindCommand(self::table(), $criteria, 'a');
      $this->assignments[$key] = $command->queryAll();
    }

    return $this->assignments[$key];
  }

  /**
   * @param string $modelName
   * @param CDbCriteria $defaultCriteria
   *
   * @return FActiveRecord[]
   */
  public function getModels($modelName, CDbCriteria $defaultCriteria = null)
  {
    $criteria = new CDbCriteria();
    $criteria->join = 'JOIN `'.Product::table().'` AS p ON p.id = a.product_id';
    $criteria->compare('p.visible', 1);
    $this->addAssignmentCondition($criteria, true, self::getFieldByModel($modelName));

    if( $defaultCriteria )
      $criteria->mergeWith($defaultCriteria);

    return $modelName::model()->findAll($criteria);
  }

  /**
   * @param CDbCriteria $criteria
   * @param bool $distinct
   * @param string $on default 'product_id'
   */
  public function addAssignmentCondition(CDbCriteria $criteria, $distinct = true, $on = 'product_id')
  {
    $tableName = self::table();

    if( !empty($criteria->condition) && (empty($criteria->join) || strpos($criteria->join, $tableName) === false) )
    {
      $criteria->join = 'JOIN '.$this->dbConnection->schema->quoteTableName($tableName) .' AS a ON a.'.$on.' = t.id '.$criteria->join;
      $criteria->distinct = $distinct;
      $criteria->compare('a.visible', 1);
    }
  }

  public static function getFieldByModel($modelName)
  {
    return lcfirst(str_replace('Product', '', $modelName)).'_id';
  }

  private function buildCriteria(CDbCriteria $defaultCriteria = null)
  {
    $criteria = new CDbCriteria(array('distinct' => true));

    $criteria->select = array(
      'a.section_id, a.collection_id, a.category_id, a.type_id',
      'section.name AS section_name, section.url AS section_url, section.position AS section_position',
      'type.name AS type_name, type.url AS type_url, type.position AS type_position',
      'category.name AS category_name, category.url AS category_url',
      'collection.name AS collection_name, collection.url AS collection_url',
    );

    $condition = implode(" AND ", array(
      'a.visible = 1',
      'product.visible = 1',
    ));

    $join = array(
      'section' => ProductSection::table(),
      'type' => ProductType::table(),
      'category' => ProductCategory::table(),
      'collection' => ProductCollection::table(),
      'product' => Product::table(),
    );

    array_walk($join, function(&$value, $key){
      $value = PHP_EOL.'LEFT OUTER JOIN `'.$value.'` AS '.$key.' ON '.$key.'.id = a.'.$key.'_id';
    });

    $criteria->join = implode(" ", $join);
    $criteria->condition = $condition;
    $criteria->distinct = true;

    if( $defaultCriteria !== null )
      $criteria->mergeWith($defaultCriteria);

    return $criteria;
  }
}