<?php

class ContactSetting extends CFormModel
{
    public $pager;
    public $feedback;

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
            array('pager', 'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>100),
            array('feedback', 'boolean')
        );
    }

    public function attributeLabels()
    {
        return array(
            'pager' => Yii::t('site', 'Total per page'),
            'feedback' => Yii::t('site', 'Enabled feedback form')
        );
    }

}