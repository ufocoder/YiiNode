<?php

class StoreController extends ControllerAdmin
{

    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionView($id)
    {
        $this->render('/admin/store/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model_class = "CatalogStore";
        $model = new $model_class;
        $model->isNewRecord = true;

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->save()){
                Yii::app()->user->setFlash('success', Yii::t('site', 'Store was created successful!'));
                $this->redirect(array('/store/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";
        $this->render('/admin/store/create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "CatalogStore";
        $model=$this->loadModel($id);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if($model->save()){
                Yii::app()->user->setFlash('success', Yii::t('site', 'Store was updated successful!'));
                $this->redirect(array('/store/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";
        $this->render('/admin/store/update',array(
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
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/store/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionIndex()
    {
        $model_class = "CatalogStore";
        $model=new $model_class('search');
        $model->unsetAttributes();

        if(isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->layout = "application.modules.catalog.views.admin.layouts.main";
        $this->render('/admin/store/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model = CatalogStore::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='Store-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}