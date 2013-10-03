<?php

class DefaultController extends Controller
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

    public function actionIndex()
    {
        $class = "Feedback";
        $model = new $class;

        if (isset($_POST[$class])){

            $feedbackNotification = Yii::app()->getSetting('feedbackNotification');
            $feedbackEmail = Yii::app()->getSetting('feedbackEmail');

            $model->attributes = $_POST[$class];
            $model->id_node = Yii::app()->getNodeId();

            if ($model->save()){
                if ($feedbackNotification && !empty($feedbackEmail)){
                    $subject = Yii::t("site", "Feedback notification");
                    $content = $this->renderPartial('//email/feedback/notification', array('feedback'=>$model), true);
                    FeedbackModule::sendMail($feedbackEmail, $subject, $content);
                }
                Yii::app()->user->setFlash('success', Yii::t('site', 'Your feedback message was successfully received.'));
            }
        }

        $this->render('/index', array(
            'model' => $model
        ));
    }
}