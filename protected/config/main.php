<?php
/**
 * Main configuration file
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
return array(
    'name' => 'Application name',
    'language' => 'ru',
    'sourceLanguage' =>'ru',
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'preload'=>array(
        'log',
        'bootstrap'
    ),

    'import'=>array(
        'application.behaviors.*',
        'application.components.*',
        'application.models.*',
        'application.widgets.*',
        // mail
        'ext.yii-mail.YiiMailMessage',
        'ext.YiiMailer.YiiMailer',
    ),

    'behaviors'=> array(
        array(
            'class'=>'NodeBehavior'
        ),
    ),

    'modules'=>array(
        'admin',
        'articles',
        'page',
        'user'
    ),

    /* begin: components */

    'components'=> array(
        'bootstrap'=>array(
            'class'=>'ext.bootstrap.components.Bootstrap'
        ),

        'authManager'=>array(
            'class' => 'phpAuthManager',
        ),

        'db'=> array(
            'connectionString'      => 'mysql:host=localhost;dbname=node',
            'enableProfiling'       => YII_DEBUG,
            'enableParamLogging'    => YII_DEBUG,
            'username'              => 'root',
            'password'              => '',
            'charset'               => 'utf8',
            'tablePrefix'           => '',
            'schemaCachingDuration' => 5000,
        ),

        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.email',
            'logging' => true,
            'dryRun' => false
        ),

        'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),

        'log'=>array(
            'class'=>'CLogRouter',
            'enabled'=>YII_DEBUG,
            'routes'=>array(
                array(
                    'class'     => 'CFileLogRoute',
                    'levels'    => 'error, warning, debug',
                ),
            ),
        ),

        'request' => array(
            // 'enableCsrfValidation'=>true,
        ),

        'user'=>array(
            'class' => 'WebUser',
            'allowAutoLogin' => true,
            'encrypting' => 'md5',
            'salt' => null,
        ),

        'urlManager'=>array(
            'urlFormat' => 'path',
            'showScriptName'=>false,
            'useStrictParsing'=>true,
            'rules'=> array(
                array(
                    'class'=>'UrlRuleAdminNode'
                ),

                'admin/<module:(user)>/<controller:\w+>/<action:\w+>/*' => '<module>/admin/<controller>/<action>',
                'admin/<module:(user)>/<controller:\w+>' => '<module>/admin/<controller>',
                'admin/<module:(user)>' => '<module>/admin/default/index',

                'admin/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',
                'admin/<controller:\w+>' => 'admin/<controller>',
                'admin/' => 'admin/default/index',

                'user/<controller:\w+>/<action:\w+>' => 'user/<controller>/<action>',
                'user/<controller:\w+>' => 'user/<controller>',
                'user/' => 'user/default/index',

                array(
                    'class' => 'UrlRuleModuleNode'
                ),
            )
        ),
    ),
    /* end: components */

    'params'=>array(
        'version' => '0.1'
    ),

);