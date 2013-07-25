<?php
/**
 * Главный контроллер 'AdminModule'
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class RecoveryController extends ControllerAuth
{
    /**
     * Список действий [добавляем captcha]
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
                'users' => array('?'),
            ),
            array('deny')
        );
    }

    public function actionIndex()
    {
        $class_change = "FormChangePassword";
        $class_recovery = "FormRecovery";

        $form = new $class_recovery;

        // если пользователь авторизован переадресовываем его
        if (!Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->user->returnUrl);
        else
        {
            $email = ((isset($_REQUEST['email'])) ? strval($_REQUEST['email']) : '');
            $activekey = ((isset($_REQUEST['activekey'])) ? strval($_REQUEST['activekey']) : '');

            // форма изменения пароля поля [по ключу]
            if ($email && $activekey)
            {
                $form_change = new $class_change;
                $form_change->scenario = 'recovery';
                $user = User::model()->findByAttributes(array('email' => $email));

                if (isset($user) && $user->activekey == $activekey)
                {
                    if (isset($_POST[$class_change]))
                    {
                        $form_change->attributes = $_POST[$class_change];
                        if ($form_change->validate())
                        {
                            $user->password = Yii::app()->user->encrypting($form_change->password);
                            $user->activekey = Yii::app()->user->encrypting(microtime() . $form_change->password);
                            $user->save(false);
                            Yii::app()->user->setFlash('success', Yii::t("site", "New password is saved."));
                            $this->redirect(Yii::app()->user->loginUrl);
                        }
                    }
                    $this->render('/form/changepassword', array(
                        'activekey' => $activekey,
                        'email' => $user->email,
                        'model' => $form_change
                    ));
                } else {
                    Yii::app()->user->setFlash('warning', Yii::t("site", "Incorrect recovery link."));
                    $this->redirect(Yii::app()->user->recoveryUrl);
                }
            }
            // форма восстановления пароля
            else {

                if (isset($_POST[$class_recovery]))
                {
                    $form->attributes = $_POST[$class_recovery];
                    if ($form->validate()) {
                        $user = User::model()->findbyPk($form->id_user);
                        if (empty($user->email)){
                            $form->addError("login_or_email", Yii::t("site", "User's email is not exists"));
                        }
                        else
                        {
                            $activekey = md5(microtime().$user->password);
                            $user->saveAttributes(array('activekey'=>$activekey));

                            $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->user->recoveryUrl), array("activekey" => $activekey, "email" => $user->email));

                            $subject = Yii::t("site", "Password recovery");
                            $message = Yii::t("site", "To receive a new password, go to {activation_url}.", array(
                                        '{activation_url}' => $activation_url,
                                    ));

                            AdminModule::sendMail($user->email, $subject, $message);

                            Yii::app()->user->setFlash('info', Yii::t("site", "Please check your email. Instructions were sent to your email address."));
                            $this->refresh();
                        }
                    }
                }

                $this->render('/form/recovery', array('model' => $form));
            }
        }
    }
}