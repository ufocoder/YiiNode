<?php

class ActivationController extends Controller
{
    /**
     * Activation user account
     */
    public function actionIndex()
    {
        $email = $_GET['email'];
        $activkey = $_GET['activekey'];

        if ($email && $activkey) {
            $find = User::model()->notsafe()->findByAttributes(array('email' => $email));
            if (isset($find) && $find->status) {
                $content = Yii::t("site", "You account is active.");
            } elseif (isset($find->activkey) && ($find->activkey == $activkey)) {
                $find->activkey = WebUser::encrypting(microtime());
                $find->status = 1;
                $find->save();
                $content = Yii::t("site", "You account is activated.");
            } else {
                $content = Yii::t("site", "Incorrect activation URL.");
            }
        } else {
            $content = Yii::t("site", "Incorrect activation URL.");
        }

        $this->render('/user/activation', array(
            'content' => $content
        ));
    }

}