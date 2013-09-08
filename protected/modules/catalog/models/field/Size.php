<?php

namespace modules\catalog\models\field;

class Size extends \CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{catalog_field_size}}';
    }

    public function rules()
    {
        return array(
            array('title', 'required')
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'title' => 'Название',
        );
    }

    public function search()
    {
        $criteria=new \CDbCriteria;
        $criteria->compare('id',$this->id, true);
        $criteria->compare('title',$this->title);

        return new \CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}