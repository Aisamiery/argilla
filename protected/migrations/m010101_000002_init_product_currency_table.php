<?php

class m010101_000002_init_product_currency_table extends CDbMigration
{
	public function up()
	{
    Yii::app()->db->createCommand()->insert('{{product_currency}}', array('id' => 1, 'name' => 'Рубль', 'title' => 'руб'));
    Yii::app()->db->createCommand()->insert('{{product_currency}}', array('id' => 2, 'name' => 'Доллар', 'title' => '$', 'autorate_id' => 'USD'));
    Yii::app()->db->createCommand()->insert('{{product_currency}}', array('id' => 3, 'name' => 'Евро', 'title' => '&euro;', 'autorate_id' => 'EUR'));
	}

	public function down()
	{
		echo "m010101_000002_init_product_currency_table does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}