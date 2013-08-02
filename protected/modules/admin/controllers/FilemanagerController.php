<?php
/**
 * Admin module - Filemanager
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FilemanagerController extends ControllerAdmin
{
    /**
     * Main layout
     */
    public $layout = 'application.modules.admin.views.layouts.column1';

    /**
     * @return array Actions
     */
    public function actions()
    {
        return array(
            'connector' => array(
                'class'=> "ext.elfinder.ElFinderConnectorAction",
                'settings' => array(
                    'driver' => 'LocalFileSystem',
                    'root' => Yii::getPathOfAlias('webroot') . '/upload/',
                    'URL' => Yii::app()->baseUrl . '/upload/',
                    'rootAlias' => Yii::t('site', 'Home folder'),
                    'mimeDetect' => 'internal',
                    'uploadOrder'  => 'deny,allow',
                    'uploadDeny'   => array('all'),
                    'uploadAllow'  => array(
                        'image/*',
                        'application/pdf',
                        'application/x-shockwave-flash',
                        'application/pdf',
                        'application/msword',
                        'application/vnd.oasis.opendocument.text',
                        'application/vnd.ms-excel',
                        'application/vnd.ms-word',
                        'text/rtf'
                    ),
                    'perms' => array(
                        "/\.php$/i" => array(
                            'read' => false,
                            'write' => false,
                            'rm' => false,
                        )
                    ),
                )
            ),
        );
    }

    /**
     * Elfinder as filemanager [index action]
     */
    public function actionIndex()
    {
        $this->render('/filemanager/index');
    }

    /**
     * Elfinder popup widget [without layout]
     */
    public function actionEditor()
    {
        $this->layout = null;
        $this->render('/filemanager/widget');
    }
}