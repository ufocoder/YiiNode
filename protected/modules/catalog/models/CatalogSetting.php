<?php

class CatalogSetting extends CFormModel
{
    public $pager;
    public $rss;

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
            array('rss', 'boolean')
        );
    }

    public function attributeLabels()
    {
        return array(
            'pager' => Yii::t('site', 'Total per page'),
            'rss' => Yii::t('site', 'Enabled rss feed'),
        );
    }

}