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
     * @return type Access rules
     */
    public function accessRules()
    {
        $module = Yii::app()->controller->module;
        $flagRegister = Yii::app()->getSetting('userAllowRegister', $module->allowRegister);

        $rules = array();
        if ($flagRegister)
            $rules[] =  array('allow',
                'actions' => array('index', 'captcha'),
                'users' => array('*'),
            );

        $rules[] = array('deny');

        return $rules;
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

                $module = Yii::app()->controller->module;
                $typeConfirm = Yii::app()->getSetting('userConfirmTypeRegister', $module->confirmTypeRegister);

                $flagConfirmNone = $typeConfirm == $module::CONFIRM_NONE;
                $flagConfirmMail = $typeConfirm == $module::CONFIRM_MAIL;
                $flagActiveAfterRegister = Yii::app()->getSetting('userActiveAfterRegister', $module->activeAfterRegister);

                $user =  new User;
                $user->login = $form->login;
                $user->email = $form->email;
                $user->role = WebUser::ROLE_USER;
                $user->activekey = Yii::app()->user->encrypting(microtime().$form->password);
                $user->password = Yii::app()->user->encrypting($form->password);

                if ($flagActiveAfterRegister)
                    $user->status= User::STATUS_ACTIVE;
                else
                    $user->status = User::STATUS_NOACTIVE;

                if ($user->save())
                {
                    $profile->id_user = $user->id_user;
                    $profile->save();

                    if ($flagConfirmMail)
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

                    if ($flagActiveAfterRegister && $flagConfirmNone)) {
                        $identity = new UserIdentity($user->login, $form->password);
                        $identity->authenticate();
                        Yii::app()->user->login($identity, 0);
                        Yii::app()->user->setFlash('success', Yii::t("user", "Thank you for your registration."));
                        $this->redirect(Yii::app()->user->returnUrl);
                    } else {
                        if (!$flagActiveAfterRegister && $flagConfirmNone) {
                            Yii::app()->user->setFlash('success', Yii::t("user", "Thank you for your registration. Contact Admin to activate your account."));
                        } elseif($flagActiveAfterRegister && $flagConfirmNone) {
                            Yii::app()->user->setFlash('success', Yii::t("user", "Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(Yii::t("user", 'Login'),Yii::app()->controller->module->loginUrl))));
                        } elseif (!$flagActiveAfterRegister && $flagConfirmMail) {
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