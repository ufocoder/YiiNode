<?php

class BrandController extends ControllerAdmin
{

    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionView($id)
    {

        $this->render('/admin/brand/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model_class = "CatalogBrand";
        $model = new $model_class;

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->save()){

                // create file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogBrand::getUploadPath();
                    $filename   = md5(time().$model->id_brand) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $filename));
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Brand was created successful!'));
                $this->redirect(array('/brand/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/brand/create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "CatalogBrand";
        $model=$this->loadModel($id);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];

            // delete file
            if ($model->delete_image){
                $filename = CatalogBrand::getUploadPath().$model->image;
                if (file_exists($filename) && !empty($model->image))
                    unlink($filename);
                $model->saveAttributes(array('image'=>null));
            }

            if($model->save()){

                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogBrand::getUploadPath();
                    $filename   = md5(time().$model->id_brand) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename)){
                        if (!empty($model->image)){
                            $old_filename = CatalogBrand::getUploadPath().$model->image;
                            if (file_exists($old_filename) && $old_filename != $filename)
                                unlink($old_filename);
                        }
                        $model->saveAttributes(array('image' => $filename));
                    }
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Brand was updated successful!'));
                $this->redirect(array('/brand/index', 'id'=>$model->id_brand, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/brand/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->delete();
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/brand/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
    }

    public function actionIndex()
    {
        $model_class = "CatalogBrand";
        $model=new $model_class('search');
        $model->unsetAttributes();

        if(isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->layout = "application.modules.catalog.views.admin.layouts.main";
        $this->render('/admin/brand/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model = CatalogBrand::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='Brand-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}