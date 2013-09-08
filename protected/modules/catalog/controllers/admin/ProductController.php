<?php

class ProductController extends ControllerAdmin
{

    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionView($id)
    {
        $stores = CatalogStore::model()->findAll(array('index'=>'id_store'));

        $this->render('/admin/product/view', array(
            'model'=>$this->loadModel($id),
            'stores'=>$stores,
        ));
    }

    public function actionCreate()
    {
        $model_class = "CatalogProduct";
        $field_class = "CatalogProductField";
        $store_class = "CatalogStore";

        $model = new $model_class;
        $field = new $field_class;

        $stores = CatalogStore::model()->findAll(array('index'=>'id_store'));

        if (isset($_POST[$model_class]) && isset($_POST[$field_class]))
        {
            $model->attributes = $_POST[$model_class];

            // field
            $field->attributes = $_POST[$field_class];
            $model->field = $field;

            // stores
            $model_stores = array();
            if (!empty($_POST['ProductStore']))
                foreach ($_POST['ProductStore'] as $id_store => $value)
                    if (isset($stores[$id_store])){
                        $store = new CatalogProductStore;
                        $store->id_store = $id_store;
                        $store->value = $value;
                        $model_stores[] = $store;
                    }

            $model->stores = $model_stores;

            if ($model->withRelated->save(true, array('field', 'stores')))
            {
                // update count
                CatalogBrand::model()->updateProductCount();
                CatalogCategory::model()->updateProductCount();

                // create file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogProduct::getUploadPath();
                    $filename   = md5(time().$model->id_product) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $filename));
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Product was created successful!'));
                $this->redirect(array('/default/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/product/create',array(
            'model'=>$model,
            'field'=>$field,
            'stores'=>$stores,
        ));
    }

    public function actionUpdate($id)
    {

        $model_class = "CatalogProduct";
        $field_class = "CatalogProductField";
        $store_class = "CatalogStore";

        $model=$this->loadModel($id);

        $stores = CatalogStore::model()->findAll(array('index'=>'id_store'));

        $field = $model->field;
        if (empty($field)){
            $field = new $field_class;
            $field->id_product = $id;
        }

        if (isset($_POST[$model_class]) && isset($_POST[$field_class]))
        {
            $model->attributes = $_POST[$model_class];

            // field
            $field->attributes = $_POST[$field_class];
            $model->field = $field;

            // stores
            $model_stores = array();
            if (!empty($_POST['ProductStore']))
                foreach ($_POST['ProductStore'] as $id_store => $value)
                    if (isset($stores[$id_store])){
                        if (isset($model->stores[$id_store]))
                            $store = $model->stores[$id_store];
                        else
                            $store = new CatalogProductStore;

                        $store->id_store = $id_store;
                        $store->value = $value;
                        $model_stores[] = $store;
                    }

            $model->stores = $model_stores;

            if ($model->withRelated->save(true, array('field', 'stores')))
            {
                // update count
                CatalogBrand::model()->updateProductCount();
                CatalogCategory::model()->updateProductCount();

                // delete file
                if ($model->delete_image){
                    $filename = CatalogProduct::getUploadPath().$model->image;
                    if (file_exists($filename) && !empty($model->image))
                        unlink($filename);
                    $model->saveAttributes(array('image'=>null));
                }

                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogProduct::getUploadPath();
                    $filename   = md5(time().$model->id_product) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename)){
                        if (!empty($model->image)){
                            $old_filename = CatalogProduct::getUploadPath().$model->image;
                            if (file_exists($old_filename) && $old_filename != $filename)
                                unlink($old_filename);
                        }
                        $model->saveAttributes(array('image' => $filename));
                    }
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Product was created successful!'));
                $this->redirect(array('/product/view', 'id'=>$model->id_product, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/product/update',array(
            'model' => $model,
            'field' => $field,
            'stores'=> $stores
        ));
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/product/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
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

    public function loadModel($id)
    {
        $model = CatalogProduct::model()
            ->with(array('category', 'field', 'stores'))
            ->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));

        return $model;
    }

}