<?php

Yii::import('ext.easyimage.EasyImage');

class GallerySetting extends CFormModel
{
    public $pager;
    public $column;
    public $width;
    public $height;
    public $resize;

    public $showTitle;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "pager" => array(
                "default" => 10
            ),
            "column" => array(
                "default" => 2
            ),
            "width" => array(
                "default" => 150
            ),
            "height" => array(
                "default" => 100
            ),
            'resize' => array(
                EasyImage::RESIZE_NONE      => Yii::t('site', 'Default'),
                EasyImage::RESIZE_WIDTH     => Yii::t('site', 'Resize on width'),
                EasyImage::RESIZE_HEIGHT    => Yii::t('site', 'Resize on height'),
                EasyImage::RESIZE_AUTO      => Yii::t('site', 'Auto resize'),
                EasyImage::RESIZE_PRECISE   => Yii::t('site', 'Precise resize'),
            ),
            "showTitle" => array(
                "default" => true
            ),
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
        elseif (isset($settings[$setting]))
            return $settings[$setting];
    }

    public function rules()
    {
        return array(
            array('pager', 'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>100),
            array('column', 'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>12),
            array('height, width', 'numerical', 'integerOnly'=>true, 'min'=>30, 'max'=>800),
            array('resize', 'in', 'range'=>array_keys(self::values('resize'))),
            array('showTitle', 'boolean')

        );
    }

    public function attributeLabels()
    {
        return array(
            'pager' => Yii::t('site', 'Total per page'),
            'column' => Yii::t('site', 'Total column on page'),
            'width' => Yii::t('site', 'Thumb width'),
            'height' => Yii::t('site', 'Thumb height'),
            'resize' => Yii::t('site', 'Thumb resize'),
            'showTitle' => Yii::t('site', 'Show title'),
        );
    }

}