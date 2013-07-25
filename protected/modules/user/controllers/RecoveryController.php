<?php

class RecoveryController extends Controller {

    /**
     * Recovery password
     */
    public function actionIndex() {
        $form = new FormRecovery;

        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->controller->module->returnUrl);
        } else {
            $email = ((isset($_GET['email'])) ? $_GET['email'] : '');
            $activkey = ((isset($_GET['activkey'])) ? $_GET['activkey'] : '');
            if ($email && $activkey) {
                $form2 = new FormChangePassword;
                $find = User::model()->notsafe()->findByAttributes(array('email' => $email));
                if (isset($find) && $find->activkey == $activkey) {
                    if (isset($_POST['FormChangePassword'])) {
                        $form2->attributes = $_POST['FormChangePassword'];
                        if ($form2->validate()) {
                            $find->password = Yii::app()->controller->module->encrypting($form2->password);
                            $find->activkey = Yii::app()->controller->module->encrypting(microtime() . $form2->password);
                            if ($find->status == 0) {
                                $find->status = 1;
                            }
                            $find->save();
                            Yii::app()->user->setFlash('success', UserModule::t("New password is saved."));
                            $this->redirect(Yii::app()->controller->module->recoveryUrl);
                        }
                    }
                    $this->render('changepassword', array('form' => $form2));
                } else {
                    Yii::app()->user->setFlash('warning', UserModule::t("Incorrect recovery link."));
                    $this->redirect(Yii::app()->controller->module->recoveryUrl);
                }
            } else {
                if (isset($_POST['FormRecovery'])) {
                    $form->attributes = $_POST['FormRecovery'];
                    if ($form->validate()) {
                        $user = User::model()->notsafe()->findbyPk($form->user_id);
                        $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->controller->module->recoveryUrl), array("activkey" => $user->activkey, "email" => $user->email));

                        $subject = UserModule::t("Password recovery");
                        $message = UserModule::t("To receive a new password, go to {activation_url}.", array(
                                    '{activation_url}' => $activation_url,
                                ));

                        UserModule::sendMail($user->email, $subject, $message);

                        Yii::app()->user->setFlash('info', UserModule::t("Please check your email. An instructions was sent to your email address."));
                        $this->refresh();
                    }
                }
                $this->render('recovery', array('form' => $form));
            }
        }
    }

}