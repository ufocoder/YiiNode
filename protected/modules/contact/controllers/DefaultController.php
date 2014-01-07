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
        $default = ContactSetting::values('pager', 'default');
        $pager = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'pager', $default);

        $model = Contact::model()->node()->published();

        $dataProvider = new CActiveDataProvider($model, array(
            'pagination' => array(
                'pageSize' => $pager,
                'pageVar'  => 'page'
            )
        ));

        $feedback = null;
        $flag = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'feedback');

        if ($flag)
        {
            Yii::import('application.modules.feedback.models.Feedback');

            $class = "Feedback";
            $feedback = new $class;

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'form-feedback') {
                $model->scenario = 'ajax';
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            if (isset($_POST[$class])){

                $feedbackNotification = Yii::app()->getSetting('feedbackNotification');
                $feedbackEmail = Yii::app()->getSetting('feedbackEmail');

                $feedback->attributes = $_POST[$class];
                $feedback->id_node = Yii::app()->getNodeId();

                if ($feedback->save()){
                    if ($feedbackNotification && !empty($feedbackEmail)){

                        $subject = Yii::t("site", "Feedback notification");
                        $content = $this->renderPartial('//email/feedback/notification', array('feedback'=>$feedback), true);

                        WebModule::sendMail($feedbackEmail, $subject, $content);
                    }
                    Yii::app()->user->setFlash('success', Yii::t('site', 'Your feedback message was successfully received.'));
                }
            }
        }


        $this->render('/index', array(
            'feedback'      => $feedback,
            'dataProvider'  => $dataProvider
        ));
    }

    public function loadModel($id)
    {
        $model = Contact::model()->node()->published()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }

}