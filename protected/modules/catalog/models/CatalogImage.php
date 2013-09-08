<?php

class CatalogImage extends CActiveRecord
{
    public $x_image;
    public $delete_image;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/catalog/image/';

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
    protected static $uploadUrl = '/upload/catalog/image/';

    /*
     * Получить физический путь загрузки документов
     */
    public static function getUploadUrl()
    {
        return self::$uploadUrl;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_catalog_image}}';
    }

    public function rules()
    {
        return array(
            array('title', 'length', 'max'=>255),
            array('x_image', 'file', 'allowEmpty'=>false, 'on'=>'create'),
            array('x_image', 'file', 'allowEmpty'=>true, 'on'=>'update'),
            array('delete_image, enabled', 'boolean', 'allowEmpty'=>true),
        );
    }

    public function relations()
    {
        return array(
            'product' => array(self::BELONGS_TO, 'CatalogProduct', 'id_product'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id_product' => Yii::t('site', 'Product'),
            'title' => Yii::t('site', 'Title'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'x_image' => Yii::t('site', 'Image'),
            'delete_image'=> Yii::t('site', 'Delete image'),
            'image' => Yii::t('site', 'Image'),
            'enabled' => Yii::t('site', 'Enabled'),
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->condition = 't.id_product = '.$this->id_product;
        $criteria->compare('t.id',$this->id_image);
        $criteria->compare('t.title',$this->title,true);
        $criteria->compare('t.enabled',$this->enabled);

        $criteria->with = 'product';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_image DESC',
                'route'=>'/image/index',
                'params'=>array(
                    'id_product' => $this->id_product,
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true,
                )
            )
        ));
    }

    public function scopes(){
        return array(
            'published' => array(
                'condition' => 't.enabled = 1',
                'order' => 't.position'
            ),
            'recently' => array(
                'condition' => 't.enabled = 1',
                'limit' => 10
            ),
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

}