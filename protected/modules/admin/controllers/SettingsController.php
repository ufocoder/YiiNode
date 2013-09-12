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
     * Main settings
     */
    public function actionIndex()
    {
        $class_form = 'FormSettingDefault';
        $model = new $class_form;

        $model->sitename = Yii::app()->getSetting('sitename');
        $model->emailAdmin = Yii::app()->getSetting('emailAdmin');
        $model->datetimeFormat = Yii::app()->getSetting('datetimeFormat');

        if (isset($_POST[$class_form]))
        {
            $model->attributes = $_POST[$class_form];
            if ($model->validate()){
                Yii::app()->setSettings($model->attributes);
                $this->redirect(array('index'));
            }
        }
        $this->render('/settings/setting.site', array(
            'model' => $model
        ));
    }


    /**
     * User settings
     */
    public function actionUser()
    {
        $class_form = 'FormSettingUser';
        $model = new $class_form;

        $model->userAllowRegister = Yii::app()->getSetting('userAllowRegister');
        $model->userActiveAfterRegister = Yii::app()->getSetting('userActiveAfterRegister');
        $model->userConfirmTypeRegister = Yii::app()->getSetting('userConfirmTypeRegister', $model::values('confirm', 'default'));

        if (isset($_POST[$class_form]))
        {
            $model->attributes = $_POST[$class_form];
            if ($model->validate()){
                Yii::app()->setSettings($model->attributes);
                $this->redirect(array('user'));
            }
        }
        $this->render('/settings/setting.user', array(
            'model' => $model
        ));
    }
}