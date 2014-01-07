<?php

class OrderDiscount extends CActiveRecord
{

    public $discount = 0;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_order_discount}}';
    }

    public function rules()
    {
        return array(
            array('discount', 'numerical', 'min'=>0, 'max'=>100),
            array('discount', 'default', 'value'=>0),
            array('id_user, discount', 'required'),
            array('id_user', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::HAS_ONE, 'User', 'id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id_user' => Yii::t('catalog', 'User'),
            'discount' => Yii::t('catalog', 'Discount'),
        );
    }

}