<?php

class TagController extends ControllerAdmin
{
    public function actionView($id)
    {
        $this->layout = "application.modules.admin.views.layouts.column1";
        $this->render('/admin/tag/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model_class = "ArticleTag";
        $model = new $model_class;
        $model->isNewRecord = true;

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();

            if ($model->save()){
                Yii::app()->user->setFlash('success', Yii::t('site', 'Tag was created successful!'));
                $this->redirect(array('/tag/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/tag/create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "ArticleTag";
        $model = $this->loadModel($id);

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->save())
            {
                Yii::app()->user->setFlash('success', Yii::t('site', 'Form values were saved!'));
                $this->redirect(array('/tag/view', 'id'=>$model->id_article_tag, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/tag/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest)
        {
            $model = $this->loadModel($id);
            $model->delete();
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/tag/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
        }
        else
            throw new CHttpException(400, Yii::t('error', 'Invalid request. Please do not repeat this request again.'));
    }

    public function actionIndex()
    {
        $model_class = "ArticleTag";
        $model = new $model_class('search');
        $model->unsetAttributes();

        if (isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->render('/admin/tag/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model = ArticleTag::model()->node()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
