<?php

class CatalogProduct extends CActiveRecord
{
    protected static $fields;

    public $x_image;
    public $delete_image;

    /**
     * Путь для загрузки документов в файловой системе
     */
    protected static $uploadPath = 'upload/catalog/product/';

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
    protected static $uploadUrl = '/upload/catalog/product/';

    /*
     * Получить физический путь загрузки документов
     */
    public static function getUploadUrl()
    {
        return self::$uploadUrl;
    }

    public function getFilter()
    {
        $field      = Yii::app()->request->getQuery('order');
        $direction  = Yii::app()->request->getQuery('dir', null);
        $param      = Yii::app()->request->getQuery('param', null);
        $pager      = Yii::app()->request->getQuery('pager', null);

        $fields     = $this->values('filter', 'fields');
        $pagers     = $this->values('filter', 'pager');

        $keys = array_keys($fields);
        if (!empty($field) && !in_array($field, $keys))
            $field = !empty($keys[0])?$keys[0]:null;

        if ($direction!='asc')
            $direction = 'desc';

        if (!in_array($pager, $pagers))
            $pager = !empty($pagers[0])?$pagers[0]:9;

        $criteria = array();
        if (!empty($field))
        {
            if (isset($fields[$field]['column']))
            {
                if  (!empty($param))
                    $criteria = array(
                        'condition' => 't.'.$fields[$field]['column'].' = :field_'.$field,
                        'params' => array(
                            ':field_'.$field => $param
                        )
                    );
            }
            else
                $criteria = array('order'=>'t.'.$field.' '.$direction);
        }
        else
            $criteria = array('order' => 't.price ASC, t.store DESC');

        return array(
            'criteria'  => $criteria,
            'field'     => $field,
            'direction' => $direction,
            'param'     => $param,
            'pager'     => $pager
        );
    }

    public function values($setting = null, $value = null)
    {
        $settings = array(
            "filter" => array(
                "fields" => array(
                    'price'=>array(
                        'title' => Yii::t('catalog', 'By price')
                    ),
                    'brand'=>array(
                        'title' => Yii::t('catalog', 'By brand'),
                        'column' => 'id_brand',
                        'data' => CHtml::listData(CatalogBrand::model()->findAll(), 'id', 'title'),
                    ),
                    'store' => array(
                        'title' => Yii::t('catalog', 'By availability')
                    )
                ),
                "direction" => array('asc', 'desc'),
                "pager" => array(9, 18, 45)
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    public function behaviors()
    {
        return array(
            'withRelated'=>array(
                'class'=>'application.behaviors.WithRelatedBehavior',
            )
        );
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_catalog_product}}';
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('id_category, price, count, store, id_brand', 'numerical'),
            array('price, id_category, id_brand', 'default', 'setOnEmpty'=>true, 'value'=>null),
            array('article, notice, content', 'default', 'setOnEmpty'=>true, 'value'=>''),
            array('x_image', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true),
            array('enabled, delete_image', 'boolean', 'allowEmpty'=>true),
        );
    }

    public function relations()
    {
        return array(
            'field'     => array(self::HAS_ONE, 'CatalogProductField', 'id_product'),
            'stores'    => array(self::HAS_MANY, 'CatalogProductStore', 'id_product', 'index'=>'id_store'),
            'brand'     => array(self::BELONGS_TO, 'CatalogBrand', 'id_brand'),
            'category'  => array(self::BELONGS_TO, 'CatalogCategory', 'id_category'),
            'images'    => array(self::HAS_MANY, 'CatalogImage', 'id_product')
        );
    }

    public function attributeLabels()
    {
        return array(
            'id_category' => Yii::t('catalog', 'Category'),
            'id_brand' => Yii::t('catalog', 'Brand'),
            'article' => Yii::t('catalog', 'Article'),
            'title' => Yii::t('site', 'Title'),
            'notice' => Yii::t('site', 'Notice'),
            'content' => Yii::t('site', 'Content'),
            'count' => Yii::t('site', 'Count'),
            'price' => Yii::t('site', 'Price'),
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
        $criteria = new CDbCriteria;
        $criteria->compare('t.id_product',$this->id_product);
        $criteria->compare('t.id_category',$this->id_category,true);
        $criteria->compare('t.title',$this->title);
        $criteria->compare('t.enabled',$this->enabled);

        $criteria->with = array('brand', 'category');

        return new \CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_product DESC',
                'route'=>'/default/index',
                'params'=>array(
                    'nodeId' => Yii::app()->getNodeId(),
                    'nodeAdmin' => true
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
            'latest' => array(
                'order' => 'IF(`time_updated` IS NOT NULL, `time_updated`, `time_created`) DESC',
                'condition' => 't.enabled = 1 AND t.preview IS NOT NULL AND t.price != 0',
                'limit' => 12
            ),
            'brand' => array(
                'condition' => 't.id_brand = :brand'
            )
        );
    }

    public function getFields()
    {
        if (self::$fields == null){
            self::$fields = CatalogField::model()->findAll(array('index'=>'varname'));
        }
        return self::$fields;
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
            if (file_exists($filename) && $this->image)
                unlink($filename);
            return true;
        }
    }

}