<?php

class ArticleSetting extends CFormModel
{
    public $showDate;
    public $showImageList;
    public $showImageItem;
    public $fieldPosition;
    public $orderPosition;
    public $pager;
    public $rss;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "pager" => array(
                "default" => 10
            ),
            "showDate" => array(
                "default" => true
            ),
            "showImageList" => array(
                "default" => true
            ),
            "showImageItem" => array(
                "default" => false
            ),
            "fieldPosition" => array(
                "default" => false,
            ),
            "orderPosition" => array(
                "default" => 'DESC',
                "data" => array(
                    'DESC' => Yii::t('site', 'Order desc'),
                    'ASC' => Yii::t('site', 'Order asc')
                )
            ),
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    public function rules()
    {
        return array(
            array('pager', 'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>100),
            array('orderPosition', 'in', 'range'=>array_keys(self::values('orderPosition', 'data'))),
            array('showDate, showImageList, showImageItem, fieldPosition, rss', 'boolean', 'allowEmpty'=>true),
        );
    }

    public function attributeLabels()
    {
        return array(
            'showDate' => Yii::t('site', 'Show date'),
            'showImageList' => Yii::t('site', 'Show image in list'),
            'showImageItem' => Yii::t('site', 'Show image in item'),
            'fieldPosition' => Yii::t('site', 'Use position field'),
            'orderPosition' => Yii::t('site', 'Position field order'),
            'pager' => Yii::t('site', 'Total per page'),
            'rss' => Yii::t('site', 'Enabled rss feed'),
        );
    }

}