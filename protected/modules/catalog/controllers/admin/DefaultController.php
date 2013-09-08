<?php

class DefaultController extends ControllerAdmin
{

    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionSetting()
    {
        $model_class = "CatalogSetting";
        $model = new $model_class;
        $nodeId = Yii::app()->getNodeId();

        $model->pager = Yii::app()->getNodeSetting($nodeId, 'pager', $model::values('pager', 'default'));
        $model->rss = Yii::app()->getNodeSetting($nodeId, 'rss', false);

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->validate()){
                Yii::app()->setNodeSettings($nodeId, $model->attributes);
                Yii::app()->user->setFlash('success', Yii::t('site', 'Settings were successfully saved.'));
                $this->redirect(array('/default/setting', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/setting/index',array(
            'model'=>$model,
        ));
    }

    public function actionIndex()
    {
        $model_class = "CatalogProduct";
        $model=new $model_class('search');
        $model->unsetAttributes();
        if(isset($_GET[$model_class]))
            $model->attributes=$_GET[$model_class];

        $this->layout = "application.modules.catalog.views.admin.layouts.main";
        $this->render('/admin/product/index',array(
            'model'=>$model,
        ));
    }
}