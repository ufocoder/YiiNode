<?php

class LoginController extends Controller
{

    /**
     * Список действий [добовляем captcha]
     * 
     * @return type
     */
    public function actions()
    {
        return array(
            'captcha'=>array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'testLimit' => '5',
            ),
        );
    }

    /**
     * Список правил доступа
     *
     * @return type
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'captcha'),
                'users' => array('*'),
            ),
            array('deny')
        );
    }


    /**
     * Форма входа [действие по умолчанию]
     */
    public function actionIndex()
    {  
        // auth thought service with eauth extension
        $service = Yii::app()->request->getQuery('service');

        if (isset($service)) {
            $authIdentity = Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl = Yii::app()->user->returnUrl;
            $authIdentity->cancelUrl = $this->createAbsoluteUrl('site/login');

            if ($authIdentity->authenticate()) {
                $identity = new EAuthUserIdentity($authIdentity);
                if ($identity->authenticate()) {
                    Yii::app()->user->login($identity);
                    $authIdentity->redirect();
                } else {
                    $authIdentity->cancel();
                }
            }

            $this->redirect(array('site/login'));
        }

        // default auth
        $class = "FormLogin";
        $model=new $class;
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }

        $this->render('/user/login', array(
            'model'=>$model
        ));
    }

}