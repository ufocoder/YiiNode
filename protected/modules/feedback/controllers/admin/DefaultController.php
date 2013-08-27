<?php

class DefaultController extends ControllerAdmin
{
    public function actionSetting()
    {
        $model_class = "FeedbackSetting";
        $model = new $model_class;
        $nodeId = Yii::app()->getNodeId();

        $emailAdmin = Yii::app()->getSetting('emailAdmin');
        $model->feedbackNotification = Yii::app()->getSetting('feedbackNotification', $emailAdmin);

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->validate()){
                Yii::app()->setNodeSettings($nodeId, $model->attributes);
                Yii::app()->user->setFlash('success', Yii::t('site', 'Settings were successfully saved.'));
                $this->redirect(array('/default/setting', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/setting',array(
            'model'=>$model,
        ));
    }

    public function actionIndex()
    {
        $model_class = 'Feedback';
        $model = new $model_class('search');
        $model->unsetAttributes();

        if(isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->render('/admin/index',array(
            'model'=>$model,
        ));
    }

    public function actionView($id)
    {
        $this->layout = "application.modules.admin.views.layouts.column1";

        $model = $this->loadModel($id);
        $model->saveAttributes(array('time_readed'=>time()));
        $model->date_readed = !empty($model->time_readed)?date('Y-m-d H:i', $model->time_readed):null;

        $this->render('/admin/view', array(
            'model'=>$model,
        ));
    }


    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->delete();
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


    public function loadModel($id)
    {
        $model = Feedback::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}