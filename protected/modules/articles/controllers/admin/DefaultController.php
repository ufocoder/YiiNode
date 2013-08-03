<?php

class DefaultController extends ControllerAdmin
{
    public function actionView($id)
    {
        $this->layout = "application.modules.admin.views.layouts.column1";
        $this->render('/admin/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model_class = "Article";
        $model = new $model_class;
        $model->isNewRecord = true;

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();
            if ($model->save()){
                Yii::app()->user->setFlash('success', Yii::t('all', 'Article was created successful!'));
                $this->redirect(array('/default/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "Article";
        $model = $this->loadModel($id);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if($model->save()){
                Yii::app()->user->setFlash('success', Yii::t('all', 'Form values were saved!'));
                $this->redirect(array('/default/view', 'id'=>$model->id_article, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $model = $this->loadModel($id);
            $model->delete();
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/default/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionIndex()
    {
        $model_class = "Article";
        $model = new $model_class('search');
        $model->unsetAttributes();

        if(isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->render('/admin/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model = Article::model()->node()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
