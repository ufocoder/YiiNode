<?php

class FeedbackSetting extends CFormModel
{
    public $feedbackNotification;
    public $feedbackEmail;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "pager" => array(
                "default" => 10
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    public function rules()
    {
        return array(
            array('feedbackEmail', 'email'),
            array('feedbackNotification', 'boolean')
        );
    }

    public function attributeLabels()
    {
        return array(
            'feedbackEmail' => Yii::t('site', 'Feedback e-mail notification'),
            'feedbackNotification' => Yii::t('site', 'Feedback notification enabled'),
        );
    }

}