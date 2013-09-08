<?php

class CatalogStore extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_catalog_store}}';
    }

    public function rules()
    {
        return array(
            array('title, slug', 'required'),
            array('title, alttitle', 'length', 'max'=>255),
            array('notice', 'default', 'setOnEmpty'=>true, 'value'=>''),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('site', 'Title'),
            'alttitle' => Yii::t('site', 'Alt title'),
            'slug' => Yii::t('site', 'Slug'),
            'notice' => Yii::t('site', 'Notice'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'count' => Yii::t('site', 'Count'),
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id_store',$this->id_store, true);
        $criteria->compare('title',$this->title);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
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