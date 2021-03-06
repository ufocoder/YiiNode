<?php

class CategoryController extends ControllerAdmin
{


    public function actionView($id)
    {
        $this->layout = "application.modules.admin.views.layouts.column1";
        $this->render('/admin/category/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model_class = "GalleryCategory";
        $model = new $model_class;
        $model->isNewRecord = true;

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();

            if ($model->save()){
                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = GalleryCategory::getUploadPath();
                    $filename   = md5(time().$model->id_node) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() .$filename));
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Gallery category was created successful!'));
                $this->redirect(array('/category/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/category/create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "GalleryCategory";
        $model = $this->loadModel($id);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();

            // delete file
            if ($model->delete_image){
                $filename = GalleryCategory::getUploadPath().$model->image;
                if (file_exists($filename) && !empty($model->image))
                    unlink($filename);
                $model->saveAttributes(array('image'=>null));
            }

            if ($model->save()){
                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = GalleryCategory::getUploadPath();
                    $filename   = md5(time().$model->id_node) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename)){
                        if (!empty($model->image)){
                            $old_filename = GalleryCategory::getUploadPath().$model->image;
                            if (file_exists($old_filename) && $old_filename != $filename)
                                unlink($old_filename);
                        }
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() .$filename));
                    }
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Gallery category was updated successful!'));
                $this->redirect(array('/category/view', 'id'=>$model->id_gallery_category, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/category/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->delete();
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/category/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
    }

    public function actionMoveUp($id)
    {
        $model = $this->loadModel($id);
        $model->moveUp();

        $redirectParams = array(
            '/category/index',
            'nodeAdmin'=>true,
            'nodeId'=>Yii::app()->getNodeId()
        );

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $redirectParams);
    }

    public function actionMoveDown($id)
    {
        $model = $this->loadModel($id);
        $model->moveDown();

        $redirectParams = array(
            '/category/index',
            'nodeAdmin'=>true,
            'nodeId'=>Yii::app()->getNodeId()
        );

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $redirectParams);
    }

    public function actionIndex()
    {
        $model_class = "GalleryCategory";
        $model = new $model_class('search');
        $model->unsetAttributes();

        if(isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->render('/admin/category/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model = GalleryCategory::model()->node()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
