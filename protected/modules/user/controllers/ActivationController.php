<?php

class ActivationController extends Controller {

    public $defaultAction = 'activation';

    /**
     * Activation user account
     */
    public function actionActivation() {

        $email = $_GET['email'];
        $activkey = $_GET['activekey'];

        if ($email && $activkey) {
            $find = User::model()->notsafe()->findByAttributes(array('email' => $email));
            if (isset($find) && $find->status) {
                $content = UserModule::t("You account is active.");
            } elseif (isset($find->activkey) && ($find->activkey == $activkey)) {
                $find->activkey = UserModule::encrypting(microtime());
                $find->status = 1;
                $find->save();
                $content = UserModule::t("You account is activated.");
            } else {
                $content = UserModule::t("Incorrect activation URL.");
            }
        } else {
            $content = UserModule::t("Incorrect activation URL.");
        }

        $this->render('/user/activation', array(
            'content' => $content
        ));
    }

}