<?php

class AddController extends Controller
{
    public $layout = "//layouts/recipe";

    /**
     * Добавление рецепта
     */
    public function actionIndex()
    {
        $class = 'Recipe';
        $model = new $class;

        if(isset($_POST['ajax']) && $_POST['ajax']==='recipe-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if ($model->validate()){
                $instance = CUploadedFile::getInstance($model, 'x_image');
                $extension = CFileHelper::getExtension($instance->getName());
                $pathname = Document::getUploadPath();
                $filename = md5(time().$id_company) . '.' . $extension;
                if ($instance->saveAs($pathname.$filename))
                    $model->filename = $filename;
                    
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        $this->render('/add', array(
            'model'=>$model
        ));
    }

}