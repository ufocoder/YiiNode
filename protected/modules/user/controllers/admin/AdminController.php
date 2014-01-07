<?php

class AdminController extends ControllerAdmin
{
    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionIndex()
    {
        $model=new User('search');
        $model->unsetAttributes();

        if(isset($_POST['User']))
            $model->attributes=$_POST['User'];

        $this->render('/admin/user/index',array(
            'model'=>$model,
        ));
    }


    /**
     * Displays a particular model.
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->render('/admin/user/view',array(
            'model'=>$model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new User;
        $profile=new Profile;

        $this->performAjaxValidation(array($model, $profile));

        if(isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];

            if (isset($_POST['Profile']))
                $profile->attributes = $_POST['Profile'];

            if ($model->validate() && $profile->validate()) {
                $model->password = Yii::app()->user->encrypting($model->password);
                if($model->save()) {
                    $profile->id_user=$model->id_user;
                    $profile->save();
                }
                $this->redirect(array('view','id'=>$model->id_user));
            } else $profile->validate();
        }

        $this->render('/admin/user/create',array(
            'model'=>$model,
            'profile'=>$profile,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        $profile=$model->profile;
        $this->performAjaxValidation(array($model,$profile));
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];

            if (isset($_POST['Profile']))
                $profile->attributes = $_POST['Profile'];

            if ($model->validate() && $profile->validate()) {
                $model->save();
                $profile->save();
                $this->redirect(array('view','id'=>$model->id));
            } else $profile->validate();
        }

        $this->render('/admin/user/update',array(
            'model' => $model,
            'profile' => $profile,
        ));
    }


    /**
     * Change password
     */
    public function actionChangepassword($id)
    {
        $user = $this->loadModel($id);

        $class = "FormChangePassword";
        $model = new $class;
        $model->scenario = 'change';

        if(isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if($model->validate())
            {
                $new_password = User::model()->findbyPk(Yii::app()->user->id);
                $new_password->password = Yii::app()->user->encrypting($model->password);
                $new_password->activekey = Yii::app()->user->encrypting(microtime().$model->password);
                if ($new_password->save())
                    Yii::app()->user->setFlash('success', Yii::t("site", "New password is saved."));

                $this->redirect(array("index"));
            }
        }
        $this->render('/admin/user/changepassword', array('user'=>$user,'model'=>$model));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $profile = Profile::model()->findByPk($model->id_user);
        $profile->delete();
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_POST['ajax']))
            $this->redirect(array('/admin/user/admin'));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($validate)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
        {
            echo CActiveForm::validate($validate);
            Yii::app()->end();
        }
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel($id)
    {
        $model = User::model()->findbyPk($id);
        if ($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));

        return $model;
    }

}