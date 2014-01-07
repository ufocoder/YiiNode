<?php

class OrderSetting extends CFormModel
{
    public $orderNoticeAdmin;
    public $orderNoticeManager;
    public $orderNoticeEmail;
    public $orderNoticeUser;
    public $orderDeliveryPrice;
    public $orderPager;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "orderNoticeAdmin" => array(
                "default" => null
            ),
            "orderNoticeEmail" => array(
                "default" => \Yii::app()->params['adminEmail']
            ),
            "orderNoticeManager" => array(
                "default" => null
            ),
            "orderNoticeUser" => array(
                "default" => null
            ),
            "orderDeliveryPrice" => array(
                "default" => 0
            ),
            "orderPager" => array(
                "defaul" => 10
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    public function rules()
    {
        return array(
            array('orderDeliveryPrice, orderPager', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
            array('orderNoticeEmail', 'email', 'allowEmpty'=>true),
            array('orderNoticeUser, orderNoticeAdmin, orderNoticeManager', 'boolean', 'allowEmpty'=>true)
        );
    }

    public function attributeLabels()
    {
        return array(
            'orderDeliveryPrice' => Yii::t('order', 'Delivery price'),
            'orderNoticeAdmin' => Yii::t('order', 'Notify administrator after order'),
            'orderNoticeManager' => Yii::t('order', 'Notify manager after order'),
            'orderNoticeEmail' => Yii::t('order', 'Notice email'),
            'orderNoticeUser' => Yii::t('order', 'Notify user after order')
        );
    }

}