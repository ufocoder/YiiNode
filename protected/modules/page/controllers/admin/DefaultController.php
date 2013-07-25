<?php

class DefaultController extends ControllerAdmin
{
    public function actionIndex()
    {
        $nodeId = Yii::app()->getNodeId();
        $class = "Page";
        $model = $class::model()->findByPk($nodeId);

        if (empty($model))
            $model = new $class;

        if (isset($_POST[$class])){
            $model->attributes = $_POST[$class];
            if ($model->save()){
                Yii::app()->user->setFlash('sucess', Yii::t('site', 'Page was saved.'));
            }
        }

        $this->render('/admin/index',array(
            'model'=>$model,
        ));

    }
}
