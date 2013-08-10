<?php
/**
 * Admin module - Settings controller
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class SettingsController extends ControllerAdmin
{
    /**
     * Index page with last changes widgets
     */
    public function actionIndex()
    {
        $class_form = 'FormSettingDefault';
        $model = new $class_form;

        $model->sitename = Yii::app()->getSetting('sitename');
        $model->emailAdmin = Yii::app()->getSetting('email_admin');
        $model->datetimeFormat = Yii::app()->getSetting('datetimeFormat');

        if (isset($_POST[$class_form]))
        {
            $model->attributes = $_POST[$class_form];
            if ($model->validate()){
                Yii::app()->setSettings($model->attributes);
                $this->redirect(array('index'));
            }
        }
        $this->render('/settings/index', array(
            'model' => $model
        ));
    }
}