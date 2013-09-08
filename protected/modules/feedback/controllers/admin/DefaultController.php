<?php

class DefaultController extends ControllerAdmin
{
    public function actionSetting()
    {
        $model_class = "FeedbackSetting";
        $model = new $model_class;
        $nodeId = Yii::app()->getNodeId();

        if (!empty($nodeId)){
            $model->feedbackEmail = Yii::app()->getNodeSetting($nodeId, 'feedbackEmail');
            $model->feedbackNotification = Yii::app()->getNodeSetting($nodeId, 'feedbackNotification');
        }
        else{
            $model->feedbackEmail = Yii::app()->getSetting('feedbackEmail');
            $model->feedbackNotification = Yii::app()->getSetting('feedbackNotification');
        }

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->validate()){
                if (!empty($nodeId)){
                    Yii::app()->setNodeSettings($nodeId, $model->attributes);
                    $redirectUrl = array('/default/setting', 'nodeAdmin'=>true, 'nodeId'=>$nodeId);
                }
                else{
                    Yii::app()->setSettings($model->attributes);
                    $redirectUrl = array('/admin/feedback/default/setting');
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Settings were successfully saved.'));

                $this->redirect($redirectUrl);
            }
        }

        $this->render('/admin/setting',array(
            'model'=>$model,
        ));
    }

    public function actionIndex()
    {
        $nodeId = Yii::app()->getNodeId();

        $model_class = 'Feedback';
        $model = new $model_class;
        $model->search($nodeId);
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/default/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
    }


    public function loadModel($id)
    {
        $nodeId = Yii::app()->getNodeId();

        $model = Feedback::model();

        if (!empty($nodeId))
            $model->node();

        $model = $model->findByPk($id);

        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}