<?php

class RegistrationController extends Controller
{
    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
                'testLimit'=>'2'
            ),
        );
    }

    /**
     * Registration user
     */
    public function actionIndex()
    {
        $form       = new FormRegistration;
        $profile    = new Profile;

        if (Yii::app()->user->id)
            $this->redirect(Yii::app()->user->profileUrl);

        if(isset($_POST['FormRegistration']))
        {
            $form->attributes = $_POST['FormRegistration'];
            $profile->attributes = isset($_POST['Profile'])?$_POST['Profile']:array();

            if ($form->validate() && $profile->validate())
            {
                $user =  new User;
                $user->login = $form->login;
                $user->email = $form->login;
                $user->activekey = Yii::app()->user->encrypting(microtime().$form->password);
                $user->password = Yii::app()->user->encrypting($form->password);

                if (Yii::app()->controller->module->activeAfterRegister)
                    $user->status= User::STATUS_ACTIVE;
                else
                    $user->status = User::STATUS_NOACTIVE;

                if ($user->save())
                {
                    $profile->id_user = $user->id_user;
                    $profile->save();

                    if (Yii::app()->controller->module->sendActivationMail)
                    {
                        $activation_url = $this->createAbsoluteUrl('/user/activation/activation', array("activekey" => $model->activkey, "email" => $model->email));
                        UserModule::sendMail(
                            $model->email,
                            Yii::t("user", "You registered from {site_name}", array(
                                '{site_name}'=>Yii::app()->name
                            )),
                            Yii::t("user", "Please activate you account go to {activation_url}", array(
                                '{activation_url}'=>$activation_url
                            ))
                        );
                    }

                    if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
                        $identity = new UserIdentity($user->login, $form->password);
                        $identity->authenticate();
                        Yii::app()->user->login($identity, 0);
                        $this->redirect(Yii::app()->controller->module->returnUrl);

                    } else {
                        if (!Yii::app()->controller->module->activeAfterRegister && !Yii::app()->controller->module->sendActivationMail) {
                            Yii::app()->user->setFlash('success', Yii::t("user", "Thank you for your registration. Contact Admin to activate your account."));
                        } elseif(Yii::app()->controller->module->activeAfterRegister && Yii::app()->controller->module->sendActivationMail==false) {
                            Yii::app()->user->setFlash('success', Yii::t("user", "Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(Yii::t("user", 'Login'),Yii::app()->controller->module->loginUrl))));
                        } elseif(Yii::app()->controller->module->loginNotActiv) {
                            Yii::app()->user->setFlash('success', Yii::t("user", "Thank you for your registration. Please check your email or login."));
                        } else {
                            Yii::app()->user->setFlash('success', Yii::t("user", "Thank you for your registration. Please check your email."));
                        }
                        $this->refresh();
                    }
                }

            }
        }
        $this->render('/user/registration', array('model'=>$form, 'profile'=>$profile));
    }
}