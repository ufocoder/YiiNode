<?php

class ArticleSetting extends CFormModel
{
    public $pager;

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
            array('pager', 'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>100)
        );
    }

    public function attributeLabels()
    {
        return array(
            'pager' => Yii::t('all', 'Total per page'),
        );
    }

}