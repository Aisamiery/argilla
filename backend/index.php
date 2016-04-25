<?php
/**
 * @var GlobalConfig $globalConfig
 */
require_once __DIR__.'/../protected/config/global_config.php';

require_once($globalConfig->frameworkPath.'/yii.php');
require_once($globalConfig->backendPath.'/components/BApplication.php');

$app = new BApplication($globalConfig->backendConfigPath.'/backend.php');
$app->run();