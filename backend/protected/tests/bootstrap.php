<?php
$config = require_once __DIR__.'/../config/test.php';

require_once(__DIR__.'/../../../../yii/framework/yiit.php');
require_once(__DIR__.'/components/BTestApplication.php');

$app = new BTestApplication($config);
$app->initModules();