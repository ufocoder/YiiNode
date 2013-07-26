<?php
/**
 * Admin module - Filemanager
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FilemanagerController extends ControllerAdmin
{
    public $layout = 'application.modules.admin.views.layouts.column1';

    /**
     * Массив действий
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

    public function actionIndex()
    {
        $this->render('/filemanager/index');
    }

    public function actionEditor()
    {
        $this->render('/filemanager/widget');
    }
}