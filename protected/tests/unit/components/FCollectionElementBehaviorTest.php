<?php
/**
 * @author Alexey Tatarivov <tatarinov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package frontend.tests.unit.compontnts
 */
Yii::import('frontend.tests.components.collection.*');

class FCollectionElementBehaviorTest extends CDbTestCase
{
  protected $fixtures = array(
    'product' => 'Product',
  );

  public function testGetSum()
  {
    $element = new TestCollectionElement();
    $element->attachBehaviors($element->behaviors());
    $element->price = 3020.40;

    $collectionElement = new FCollectionElement(array('id' => 500, 'type' => 'product', 'amount' => 3));
    $element->setCollectionElement($collectionElement);

    $this->assertEquals($element->sum, 9061.2);
  }

  public function testCollectionItemsListData()
  {
    $collection = new FCollection('test', array('size'), false);
    $index = $collection->add(array(
      'id' => 500,
      'type' => 'product',
      'amount' => 3,
      'items' => array(
        'size' => '15',
        'options' => array(
          array(
            'id' => 501,
            'type' => 'product'
          ),
          array(
            'id' => 502,
            'type' => 'product'
          ),
          array(
            'id' => 501,
            'type' => 'product'
          ),
        )
      )
    ));

    $this->assertEquals($collection[$index]->collectionItemsListData('options', 'collectionIndex', 'id'), array(
      1 => 501,
      2 => 502,
      3 => 501
    ));
  }
}