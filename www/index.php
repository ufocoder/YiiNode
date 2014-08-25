<?php

error_reporting(E_ALL);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// autoload
require dirname(__FILE__).'/../vendor/autoload.php';

// path
$yii = dirname(__FILE__) . '/../vendor/yiisoft/yii/framework/yiilite.php';

// change the following paths if necessary
$config = dirname(__FILE__).'/../protected/config/development.php';

require_once($yii);
Yii::createWebApplication($config)->run();