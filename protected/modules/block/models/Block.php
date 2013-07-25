<?php

class Block extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{data_block}}';
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('content', 'default', 'setOnEmpty' => true, 'value'=>'')
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('all', 'Title'),
            'time_created' => Yii::t('all', 'Time created'),
            'time_updated' => Yii::t('all', 'Time updated'),
            'content' =>  Yii::t('all', 'Content'),
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
                $this->time_created = time();
            else
                $this->time_updated = time();

            return true;
        }
        else
            return false;
    }

}