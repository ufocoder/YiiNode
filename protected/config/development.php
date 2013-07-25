<?php
/**
 * Development configuration file
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
return CMap::mergeArray(
    require_once(dirname(__FILE__).'/main.php'),
    array(
        'components' => array(
            'log'=>array(
                'class'=>'CLogRouter',
                'enabled'=>YII_DEBUG,
                'routes'=>array(
                    array(
                        'class'     => 'CFileLogRoute',
                        'levels'    => 'error, warning, debug',
                    ),
                    array(
                        'class'     => 'application.extensions.debug.YiiDebugToolbarRoute',
                        'ipFilters' => array('127.0.0.1', '195.66.89.207'),
                    ),
                ),
            ),
        ),
    )
);

?>