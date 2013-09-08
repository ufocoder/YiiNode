<?php

class CategoryController extends ControllerAdmin
{

    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionIndex()
    {
        $model = CatalogCategory::model();




        $this->layout = "application.modules.catalog.views.admin.layouts.main";
        $this->render('/admin/category/index', array(
            'data' => array()
        ));
    }

    public function actionCreate()
    {
        $model_class = "CatalogCategory";
        $model = new $model_class;
        $model->scenario = 'create';

        if (!empty($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->validate())
            {
                $model->saveAsNode();

                // create file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogCategory::getUploadPath();
                    $filename   = md5(time().$model->id_category) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $filename));
                }

                if (Yii::app()->request->isAjaxRequest){
                    echo CJSON::encode(array('success'=>true));
                    Yii::app()->end();
                }else{
                    Yii::app()->user->setFlash('success', Yii::t('site', 'Category was created successful!'));
                    $this->redirect(array('/category/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
                }
            }
        }

        $categories = CHtml::listData(CatalogCategory::model()->tree()->findAll(), 'id_category', 'title');

        $this->render('/admin/category/create',array(
            'model'      => $model,
            'categories' => $categories
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "CatalogCategory";
        $model=$this->loadModel($id);
        $model->scenario = 'update';

        if (!empty($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->validate())
            {
                $model->saveAsNode();

                // delete file
                if ($model->delete_image){
                    $filename = CatalogCategory::getUploadPath().$model->image;
                    if (file_exists($filename))
                        unlink($filename);
                    $model->saveAttributes(array('image'=>null));
                }

                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = CatalogCategory::getUploadPath();
                    $filename   = md5(time().$model->id_category) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename)){
                        if (!empty($model->image)){
                            $old_filename = CatalogCategory::getUploadPath().$model->image;
                            if (file_exists($old_filename) && $old_filename != $filename)
                                unlink($old_filename);
                        }
                        $model->saveAttributes(array('image' => $filename));
                    }
                }

                if (Yii::app()->request->isAjaxRequest){
                    echo CJSON::encode(array('success'=>true));
                    Yii::app()->end();
                }else{
                    Yii::app()->user->setFlash('success', Yii::t('site', 'Category was updated successful!'));
                    $this->redirect(array('/category/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
                }
            }

        }

        $this->render('/category/update',array(
            'model'     => $model
        ));
    }

    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $model = $this->loadModel($id);
            $model->deleteNode();

            if (Yii::app()->request->isAjaxRequest)
            {
                echo CJSON::encode(array('success'=>true));
                Yii::app()->end();
            }
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/category/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionView($id)
    {
        $this->render('/category/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function loadModel($id)
    {
        $model = modules\catalog\models\Category::model()->with('parent')->findByPk($id);
        if($model === null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='Category-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}