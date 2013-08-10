<?php
/**
 * Console application
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */

// debug
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// path
$yii = dirname(__FILE__).'/../framework/yiic.php';
$config = dirname(__FILE__).'/../protected/config/console.php';

// application
require_once($yii);