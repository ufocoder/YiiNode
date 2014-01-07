<?php

class SliderSetting extends CFormModel
{
    public $sliderEnabled;
    public $sliderEffect;
    public $sliderPauseTime;
    public $sliderAnimSpeed;
    public $sliderPauseOnHover;
    public $sliderWidth;
    public $sliderHeight;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "sliderEnabled" => array(
                "default" => false,
            ),
            "sliderEffect" => array(
                "default" => "fade",
                "data" => array(
                    "fold" => "Складывание",
                    "fade" => "Затемнение",
                    "sliceDown" => "Слайд-эффект"
                ),
            ),
            "sliderPauseTime" => array(
                "default" => 3000
            ),
            "sliderAnimSpeed" => array(
                "default" => 500
            ),
            "sliderPauseOnHover" => array(
                "default" => false
            ),
            "sliderWidth" => array(
                "default" => 980
            ),
            "sliderHeight" => array(
                "default" => 200
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    public function rules()
    {
        return array(
            array('sliderEffect', 'in', 'range'=> array_keys(self::values('sliderEffect', 'data')), 'allowEmpty'=>false),
            array('sliderPauseTime', 'numerical', 'integerOnly'=>true, 'min' => 10, 'max' => 10000, 'allowEmpty'=>false),
            array('sliderAnimSpeed', 'numerical', 'integerOnly'=>true, 'min' => 10, 'max' => 10000, 'allowEmpty'=>false),
            array('sliderPauseOnHover, sliderEnabled', 'boolean', 'allowEmpty'=>true),
            array('sliderWidth, sliderHeight', 'numerical'),
            // default:
            array('sliderEnabled', 'default', 'value'=> self::values('sliderEnabled', 'default')),
            array('sliderEffect', 'default', 'value'=> self::values('sliderEffect', 'default')),
            array('sliderPauseTime', 'default', 'value'=> self::values('sliderPauseTime', 'default')),
            array('sliderAnimSpeed', 'default', 'value'=> self::values('sliderAnimSpeed', 'default')),
            array('sliderPauseOnHover', 'default', 'value'=> self::values('sliderPauseOnHover', 'default')),
        );
    }

    public function attributeLabels()
    {
        return array(
            'sliderEnabled'       => Yii::t('slider', 'Slider enabled'),
            'sliderEffect'        => Yii::t('slider', 'Effect of transition'),
            'sliderPauseTime'     => Yii::t('slider', 'Slider delay'),
            'sliderAnimSpeed'     => Yii::t('slider', 'Slide animation'),
            'sliderPauseOnHover'  => Yii::t('slider', 'Pause on hover'),
            'sliderWidth'         => Yii::t('slider', 'Width slide'),
            'sliderHeight'        => Yii::t('slider', 'Height slide'),
        );
    }

}