<?php

class Contact extends CActiveRecord
{
    /**
     * Список дат
     */
    public $date_created;
    public $date_updated;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/contact/';

    /*
     * Получить путь загрузки документов
     */
    public static function getUploadPath()
    {
        return self::$uploadPath;
    }

    /**
     * Url для загрузки документов
     */
    protected static $uploadUrl = '/upload/contact/';

    /*
     * Получить физический путь загрузки документов
     */
    public static function getUploadUrl()
    {
        return self::$uploadUrl;
    }

    public $x_image;
    public $delete_image;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_contact}}';
    }

    public function rules()
    {
        return array(
            array('title, addr, id_node', 'required'),
            array('map_long, map_lat', 'type', 'type'=>'float'),
            array('map_zoom, icq', 'numerical', 'allowEmpty'=>true),
            array('content', 'default', 'value'=>null),
            array('email', 'email'),
            array('skype, icq, phone, addr, email, timework', 'default', 'setOnEmpty'=>true, 'value'=>null),
            array('x_image', 'file', 'allowEmpty'=>true),
            array('enabled, delete_image', 'boolean', 'allowEmpty'=>true),
        );
    }

    /**
     * @return array Правила связей между моделями
     */
    public function relations()
    {
        return array(
            'Node' => array(self::BELONGS_TO, 'Node', 'id_node'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('site', 'Title'),
            'content' => Yii::t('site', 'Content'),
            'meta_keywords' => Yii::t('site', 'Meta keywords'),
            'meta_description' => Yii::t('site', 'Meta description'),
            'addr' => Yii::t('site', 'Address'),
            'image' => Yii::t('site', 'Image'),
            'icq' => Yii::t('site', 'ICQ'),
            'email' => Yii::t('site', 'E-mail'),
            'map_lat' => Yii::t('site', 'Map latitude'),
            'map_long' => Yii::t('site', 'Map longitude'),
            'map_zoom' => Yii::t('site', 'Map zoom'),
            'timework' => Yii::t('site', 'Work time'),
            'phone' => Yii::t('site', 'Phone'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'x_image' => Yii::t('site', 'Image'),
            'delete_image'=> Yii::t('site', 'Delete image'),
            'enabled' => Yii::t('site', 'Enabled')
        );
    }

    public function search()
    {
        $criteria=new \CDbCriteria;
        $criteria->compare('id_contact',$this->id_contact);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('addr', $this->addr, true);
        $criteria->compare('icq', $this->icq, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('timework', $this->timework, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('enabled', $this->enabled, true);

        return new \CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_contact DESC',
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
        ));
    }

    /**
     * Добавить значения дат
     */
    protected function afterFind()
    {
        $format = Yii::app()->getSetting('datetimeFormat', 'Y-m-d H:i');

        $this->date_created = !empty($this->time_created)?date($format, $this->time_created):null;
        $this->date_updated = !empty($this->time_updated)?date($format, $this->time_updated):null;

        parent::afterFind();
    }

    /**
     * @return array Группы условий
     */
    public function scopes(){
        return array(
            'node' => array(
                'condition' => 't.id_node = :id_node',
                'params' => array(
                    ':id_node' => Yii::app()->getNodeId()
                ),
                'order' => 't.time_created DESC'
            )   ,
            'published' => array(
                'condition' => ' t.enabled = 1 '
            )
        );
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

    /**
     * Удаляем файл после удаления модели
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $filename = self::getUploadPath().$this->image;
            if (file_exists($filename) && !empty($this->image))
                unlink($filename);
            return true;
        }
    }

}