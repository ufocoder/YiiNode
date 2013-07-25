<?php

class AdminController extends ControllerAdmin
{
    public $defaultAction = 'admin';
    
    private $_model;

    public function actionAutoComplete()
    {
        if (isset($_GET['q'])) 
        {
            $criteria = new CDbCriteria;
            //$criteria->select='username';
            $criteria->addSearchCondition('username', $_GET['q'].'%', false);

            if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
                $criteria->limit = $_GET['limit'];
            }

            $users = User::model()->findAll($criteria);

            $this->renderText(CJSON::decode($users), true);
            Yii::app()->end();
        }
}

    public function actionAdmin()
    {
        $model=new User('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_POST['User']))
            $model->attributes=$_POST['User'];

        $this->render('/admin/admin',array(
            'model'=>$model,
        ));

        /*$dataProvider=new CActiveDataProvider('User', array(
            'pagination'=>array(
                'pageSize'=>Yii::app()->controller->module->user_page_size,
            ),
        ));

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));//*/
    }


    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $model = $this->loadModel();
        $this->render('admin/view',array(
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
        $this->performAjaxValidation(array($model,$profile));
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            $model->activkey=Yii::app()->controller->module->encrypting(microtime().$model->password);
            $profile->attributes=$_POST['Profile'];
            $profile->user_id=0;
            if($model->validate()&&$profile->validate()) {
                $model->password=Yii::app()->controller->module->encrypting($model->password);
                if($model->save()) {
                    $profile->user_id=$model->id;
                    $profile->save();
                }
                $this->redirect(array('view','id'=>$model->id));
            } else $profile->validate();
        }

        $this->render('admin/create',array(
            'model'=>$model,
            'profile'=>$profile,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $model=$this->loadModel();
        $profile=$model->profile;
        $this->performAjaxValidation(array($model,$profile));
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            $profile->attributes=$_POST['Profile'];
            
            if($model->validate()&&$profile->validate()) {
                $old_password = User::model()->notsafe()->findByPk($model->id);
                if ($old_password->password!=$model->password) {
                    $model->password=Yii::app()->controller->module->encrypting($model->password);
                    $model->activkey=Yii::app()->controller->module->encrypting(microtime().$model->password);
                }
                $model->save();
                $profile->save();
                $this->redirect(array('view','id'=>$model->id));
            } else $profile->validate();
        }

        $this->render('admin/update',array(
            'model'=>$model,
            'profile'=>$profile,
        ));
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model = $this->loadModel();
            $profile = Profile::model()->findByPk($model->id);
            $profile->delete();
            $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_POST['ajax']))
                $this->redirect(array('/user/admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
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
    public function loadModel()
    {
        if($this->_model===null)
        {
            if(isset($_GET['id']))
                $this->_model=User::model()->notsafe()->findbyPk($_GET['id']);
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }
    
}