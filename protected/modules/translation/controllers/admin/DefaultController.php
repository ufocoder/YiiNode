<?php

class DefaultController extends ControllerAdmin
{
    public function actionIndex()
    {
        $model_class = "TranslationSetting";
        $model = new $model_class;
        $nodeId = Yii::app()->getNodeId();

        $model->nodeId = Yii::app()->getNodeSetting($nodeId, 'nodeId');

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->validate()){
                Yii::app()->setNodeSettings($nodeId, $model->attributes);
                Yii::app()->user->setFlash('success', Yii::t('site', 'Settings were successfully saved.'));
                $this->redirect(array('/default/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/setting',array(
            'model'=>$model,
        ));
    }
}