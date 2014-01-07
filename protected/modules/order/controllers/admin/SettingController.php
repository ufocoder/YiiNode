<?php

class SettingController extends ControllerAdmin
{
    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionIndex()
    {
        $model_class = "OrderSetting";
        $model = new $model_class;

        $nodeId = Yii::app()->getNodeId();

        $model->orderNoticeAdmin = Yii::app()->getSetting('orderNoticeAdmin', $model->values('noticeAdmin', 'default'));
        $model->orderNoticeUser = Yii::app()->getSetting('orderNoticeUser', $model->values('noticeUser', 'default'));
        $model->orderNoticeManager = Yii::app()->getSetting('orderNoticeManager', $model->values('noticeManager', 'default'));
        $model->orderNoticeEmail = Yii::app()->getSetting('orderNoticeEmail', $model->values('noticeEmail', 'default'));
        $model->orderDeliveryPrice = Yii::app()->getSetting('orderDeliveryPrice', $model->values('deliveryPrice', 'default'));

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'settings-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST[$model_class])){
            $model->attributes = $_POST[$model_class];
            if ($model->validate()){
                Yii::app()->setSettings($model->attributes);
                Yii::app()->user->setFlash('success', Yii::t('site', 'Form values were saved!'));
                $this->redirect(array('index'));
            }
        }

        $this->render('/admin/setting/index', array('model'=>$model));
    }
}