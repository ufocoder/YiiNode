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
                'class'=> "ext.elfinder2.ElFinderConnectorAction",
                'options' => array(
                    'debug'=>true,
                    'roots'=>array(
                        array(
                            'driver'    => 'LocalFileSystem',
                            'path'      => Yii::getPathOfAlias('webroot') . '/upload/',
                            'URL'       => Yii::app()->baseUrl . '/upload/',
                            'attributes'   => array(
                                array(
                                    'pattern'   => '/\.php$/i',
                                    'read'      => false,
                                    'write'     => false,
                                    'hidden'    => true,
                                    'locked'    => true
                                )
                            ),
                            'acceptedName' => 'validateAcceptedName',
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
                            )
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

/**
 * Validate filename
 */
function validateAcceptedName($name)
{
    if (preg_match('/^\w[\w\s\.\%\-\(\)\[\]]*$/u', $name))
    {
        $denied = array("php");
        $names = explode(".", $name);
        $ext = end($names);
        return !in_array($ext, $denied);
    }else
        return false;
}