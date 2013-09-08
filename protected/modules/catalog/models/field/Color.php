<?php

namespace modules\catalog\models\field;

class Color extends \CActiveRecord
{
    public function tableName()
    {
        return '{{catalog_field_color}}';
    }

    public function rules()
    {
        return array(
            array('title, code', 'required')
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
            'code' => 'Код',
        );
    }

    public function search()
    {
        $criteria=new \CDbCriteria;
        $criteria->compare('id',$this->id, true);
        $criteria->compare('title',$this->title);
        $criteria->compare('code',$this->enabled,true);

        return new \CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}