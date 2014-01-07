<?php

class DefaultController extends ControllerAdmin
{
    public function actionSetting()
    {
        $model_class = "GallerySetting";
        $model = new $model_class;
        $nodeId = Yii::app()->getNodeId();

        $model->pager = Yii::app()->getNodeSetting($nodeId, 'pager', $model::values('pager', 'default'));
        $model->column = Yii::app()->getNodeSetting($nodeId, 'column', $model::values('column', 'default'));
        $model->width = Yii::app()->getNodeSetting($nodeId, 'width', $model::values('width', 'default'));
        $model->height = Yii::app()->getNodeSetting($nodeId, 'height', $model::values('height', 'default'));
        $model->showTitle = Yii::app()->getNodeSetting($nodeId, 'showTitle', $model::values('showTitle', 'default'));
        $model->resize = Yii::app()->getNodeSetting($nodeId, 'resize');

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if ($model->validate()){
                Yii::app()->setNodeSettings($nodeId, $model->attributes);
                Yii::app()->user->setFlash('success', Yii::t('site', 'Settings were successfully saved.'));
                $this->redirect(array('/default/setting', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/setting',array(
            'model'=>$model,
        ));
    }

    public function actionView($id)
    {
        $this->layout = "application.modules.admin.views.layouts.column1";
        $this->render('/admin/image/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate($id_category = null)
    {
        $model_class = "GalleryImage";
        $model = new $model_class;
        $model->isNewRecord = true;

        if (isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();
            if (empty($model->id_gallery_category))
                $model->id_gallery_category = null;

            if ($model->save()){
                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = GalleryImage::getUploadPath();
                    $filename   = md5(time().Yii::app()->getNodeId()) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() .$filename));
                }
                Yii::app()->user->setFlash('success', Yii::t('site', 'Gallery image was created successful!'));
                $this->redirect(array('/default/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/image/create',array(
            'model'=>$model,
        ));
    }


    public function actionUpdate($id)
    {
        $model_class = "GalleryImage";
        $model = $this->loadModel($id);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();
            if (empty($model->id_gallery_category))
                $model->id_gallery_category = null;

            if ($model->save()){
                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = GalleryImage::getUploadPath();
                    $filename   = md5(time().Yii::app()->getNodeId()) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() .$filename));
                }
                Yii::app()->user->setFlash('success', Yii::t('site', 'Gallery image was updated successful!'));
                $this->redirect(array('/default/view', 'id'=>$model->id_gallery_image, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/admin/image/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->delete();
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/default/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
    }

    public function actionMoveUp($id)
    {
        $model = $this->loadModel($id);
        $model->moveUp();

        $redirectParams = array(
            '/default/index',
            'nodeAdmin'=>true,
            'nodeId'=>Yii::app()->getNodeId()
        );

        if (!empty($id_category))
            $redirectParams['id_category'] = $id_category;

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $redirectParams);
    }

    public function actionMoveDown($id)
    {
        $model = $this->loadModel($id);
        $model->moveDown();

        $redirectParams = array(
            '/default/index',
            'nodeAdmin'=>true,
            'nodeId'=>Yii::app()->getNodeId()
        );

        if (!empty($id_category))
            $redirectParams['id_category'] = $id_category;

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $redirectParams);
    }

    public function actionIndex($id_category = null)
    {
        $category = null;
        if (!empty($id_category))
            $category = $this->loadCategory($id_category);

        $model_class = "GalleryImage";
        $model = new $model_class;
        $model->search($id_category);
        $model->unsetAttributes();

        if (isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->render('/admin/image/index',array(
            'model' => $model,
            'id_category' => $id_category,
            'category' => $category
        ));
    }

    public function loadModel($id)
    {
        $model = GalleryImage::model()->node()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadCategory($id)
    {
        $model = GalleryCategory::model()->node()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
