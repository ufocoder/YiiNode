<?php

class ImageController extends ControllerAdmin
{
    public $layout = "application.modules.admin.views.layouts.column1";

    public $data_product;

    public function actionView($id_product, $id)
    {
        $this->loadProduct($id_product);

        $this->render('/admin/image/view', array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate($id_product)
    {
        $product = $this->loadProduct($id_product);

        $model_class = "CatalogImage";
        $model = new $model_class;
        $model->scenario = 'create';

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_product = $product->id_product;
            if ($model->save()){

                // create file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogImage::getUploadPath();
                    $filename   = md5(time().$product->id_product.$model->id_image) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $filename));
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Product was created successful!'));
                $this->redirect(array('/image/index', 'id_product'=>$product->id_product, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/image/create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id_product, $id)
    {
        $product = $this->loadProduct($id_product);

        $model_class = "CatalogImage";
        $model=$this->loadModel($id);
        $model->scenario = 'update';

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];

            if($model->save()){

                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogImage::getUploadPath();
                    $filename   = md5(time().$product->id_product.$model->id_image) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename)){
                        if (!empty($model->image)){
                            $old_filename = CatalogImage::getUploadPath().$model->image;
                            if (file_exists($old_filename) && $old_filename != $filename)
                                unlink($old_filename);
                        }
                        $model->saveAttributes(array('image' => $filename));
                    }
                }
                Yii::app()->user->setFlash('success', Yii::t('site', 'Product was updated successful!'));
                $this->redirect(array('/image/index', 'id_product'=>$product->id_product, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/image/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id_product, $id)
    {
        $product = $this->loadProduct($id_product);

        $model = $this->loadModel($id);
        $model->delete();
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/image/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
    }

    public function actionIndex($id_product)
    {
        $product = $this->loadProduct($id_product);

        $model_class = "CatalogImage";
        $model=new $model_class('search');
        $model->unsetAttributes();

        if(isset($_GET[$model_class]))
            $model->attributes=$_GET[$model_class];

        $model->id_product = $product->id_product;

        $this->layout = "application.modules.catalog.views.admin.layouts.main";
        $this->render('/admin/image/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model = CatalogImage::model()->with('product')->findByPk($id);
        if($model === null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }

    public function loadProduct($id_product)
    {
        $this->data_product = CatalogProduct::model()->findByPk($id_product);
        if (empty($this->data_product))
            throw new CHttpException(404, Yii::t('error', 'Product data is empty'));
        return $this->data_product;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='image-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}