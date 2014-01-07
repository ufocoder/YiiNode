<?php

class AdminController extends ControllerAdmin
{
    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionSetting()
    {
        $model_class = "SliderSetting";
        $model = new $model_class;

        $settings = array(
            'sliderEnabled', 'sliderEffect', 'sliderPauseTime',
            'sliderAnimSpeed', 'sliderPauseOnHover', 'sliderHeight',
            'sliderWidth'
        );

        foreach($settings as $setting)
            $model->$setting = Yii::app()->getSetting($setting, $model->values($setting, 'default'));

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'settings-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST[$model_class])){
            $model->attributes = $_POST[$model_class];
            if ($model->validate()){
                Yii::app()->setSettings($model->attributes);
                Yii::app()->user->setFlash('success', Yii::t('site', 'Settings were successfully saved.'));
                $this->redirect(array('/admin/slider/admin/setting'));
            }
        }

        $this->module->initColorpicker();

        $this->render('/admin/setting/index', array('model'=>$model));
    }

    public function actionView($id)
    {
        $this->render('/admin/item/view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model_class = "Slider";

        $model = new $model_class;
        $model->scenario = 'create';

        $this->performAjaxValidation($model_class);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];

            if ($model->save()){
                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = Slider::getUploadPath();
                    $filename   = md5(time().$model->id_slider) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() . $filename));
                }

                Yii::app()->user->setFlash('success', Yii::t('slider', 'Slide was created successful!'));
                $this->redirect(array('index'));
            }
        }

        $this->render('/admin/item/create', array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "Slider";
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if($model->save()){
                // upload file
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = Slider::getUploadPath();
                    $filename   = md5(time().$model->id_slider) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() . $filename));
                }

                Yii::app()->user->setFlash('success', Yii::t('slider', 'Slider was updated successful!'));
                $this->redirect(array('view','id'=>$model->id_slider));
            }
        }

        $this->render('/admin/item/update',array(
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
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionMoveUp($id)
    {
        $model = $this->loadModel($id);
        $model->moveUp();

        $redirectParams = array(
            '/admin/slider/admin/index',
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
            '/admin/slider/admin/index',
        );

        if (!empty($id_category))
            $redirectParams['id_category'] = $id_category;

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $redirectParams);
    }

    public function actionIndex()
    {
        $model_class = "Slider";

        $model=new $model_class('search');
        $model->unsetAttributes();

        if (isset($_POST[$model_class]))
            $model->attributes=$_POST[$model_class];

        $this->render('/admin/item/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model_class = "Slider";
        $model = $model_class::model()->findByPk($id);

        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }

    protected function performAjaxGrid($model)
    {
        if(isset($_GET['ajax']))
        {
            $this->render('/admin/item/_grid', array(
                'model'=>$model,
            ));
            Yii::app()->end();
        }
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='slider-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}