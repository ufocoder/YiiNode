<?php

class DefaultController extends ControllerAdmin
{
    public function actionSetting()
    {
        $model_class = "ContactSetting";
        $model = new $model_class;
        $nodeId = Yii::app()->getNodeId();

        $model->pager = Yii::app()->getNodeSetting($nodeId, 'pager', $model::values('pager', 'default'));
        $model->feedback = Yii::app()->getNodeSetting($nodeId, 'feedback', false);

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
        $this->render('/admin/view', array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model_class = "Contact";
        $model = new $model_class;
        $model->isNewRecord = true;

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();

            if ($model->save()){

                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = Article::getUploadPath();
                    $filename   = md5(time().$model->id_node) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $filename));
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Contact was created successful!'));
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
        $model_class = "Contact";
        $model=$this->loadModel($id);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $model->id_node = Yii::app()->getNodeId();

            // delete file
            if ($model->delete_image){
                $filename = Article::getUploadPath().$model->image;
                if (file_exists($filename) && !empty($model->image))
                    unlink($filename);
                $model->saveAttributes(array('image'=>null));
            }

            if($model->save()){

                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = Article::getUploadPath();
                    $filename   = md5(time().$model->id_node) . '.' . $extension;
                    if ($instance->saveAs($pathname.$filename)){
                        if (!empty($model->image)){
                            $old_filename = Article::getUploadPath().$model->image;
                            if (file_exists($old_filename) && $old_filename != $filename)
                                unlink($old_filename);
                        }
                        $model->saveAttributes(array('image' => $filename));
                    }
                }

                Yii::app()->user->setFlash('success', Yii::t('site', 'Contact was updated successful!'));
                $this->redirect(array('/default/view', 'id'=>$model->id_contact, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";
        $this->render('/admin/update',array(
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

    public function actionIndex()
    {
        $model_class = 'Contact';
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
        $model = Contact::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='Contact-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}