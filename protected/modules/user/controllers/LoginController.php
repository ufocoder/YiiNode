<?php
/**
 * User module - Login
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class LoginController extends Controller
{
    /**
     * @return type Actions
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
     * @return type Access rules
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
     * Login form [index action]
     */
    public function actionIndex()
    {
        // авторизуем, если гость
        if (Yii::app()->user->isGuest)
        {
            $class_form = 'FormLogin';
            $model = new $class_form;
            if (isset($_POST[$class_form]))
            {
                $model->attributes = $_POST[$class_form];
                if ($model->validate()){
                    $this->redirect(Yii::app()->user->returnUrl);
                }
            }
            $this->render('/user/login', array(
                'model' => $model
            ));
        }
        else
            $this->redirect(Yii::app()->user->returnUrl);
    }
}