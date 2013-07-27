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
                    'root' => Yii::getPathOfAlias('webroot') . '/upload/',
                    'URL' => Yii::app()->baseUrl . '/upload/',
                    'rootAlias' => Yii::t('site', 'Home folder'),
                    'mimeDetect' => 'none'
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