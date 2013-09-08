<?php

class CatalogBrand extends CActiveRecord
{
    public $x_image;
    public $delete_image;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/catalog/brand/';

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
    protected static $uploadUrl = '/upload/catalog/brand/';

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
        return '{{mod_catalog_brand}}';
    }

    public function rules()
    {
        return array(
            array('title, position', 'required'),
            array('notice', 'default', 'setOnEmpty'=>true, 'value'=>''),
            array('x_image', 'file', 'allowEmpty'=>true),
            array('enabled, delete_image', 'boolean', 'allowEmpty'=>true),
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
            'notice' => Yii::t('site', 'Notice'),
            'count' => Yii::t('site', 'Count'),
            'position' => Yii::t('site', 'Position'),
            'time_created' => Yii::t('site', 'Time created'),
            'time_updated' => Yii::t('site', 'Time updated'),
            'x_image' => Yii::t('site', 'Image'),
            'delete_image'=> Yii::t('site', 'Delete image'),
            'image' => Yii::t('site', 'Image'),
            'enabled' => Yii::t('site', 'Enabled'),
        );
    }

    /**
     * @return array Группы условий
     */
    public function scopes(){
        return array(
            'published' => array(
                'condition' => 't.enabled = 1 AND t.count != 0',
                'order' => 't.position'
            )
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id_brand',$this->id_brand, true);
        $criteria->compare('title',$this->title);
        $criteria->compare('notice',$this->notice, true);
        $criteria->compare('position',$this->position, true);
        $criteria->compare('enabled',$this->enabled,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_brand DESC',
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
                )
            )
        ));
    }

    public function updateProductCount()
    {
        $brandPk = 'id_brand';
        $productPk = 'id_product';

        $command = Yii::app()->db->createCommand();
        $command->select = "`".$brandPk."`, COUNT(`".$productPk."`) as `count`";
        $command->from = CatalogProduct::model()->tableName();
        $command->where = "`enabled` = 1 AND `".$brandPk."` IS NOT NULL";
        $command->group = "".$brandPk."";

        $brand_count = $command->queryAll();

        Yii::app()->db->createCommand()->update(CatalogBrand::model()->tableName(), array("count"=>'0'));

        foreach($brand_count as $item){
            $brand = CatalogBrand::model()->findByPk($item[$brandPk]);
            $brand->count = $item['count'];
            $brand->save();
        }
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