<?php

class CatalogProductStore extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{mod_catalog_product_store}}';
    }

    public function rules()
    {
        return array(
            array('id_store, value', 'required'),
            array('id_store, id_product, value', 'numerical')
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
            'title' => 'Название',
            'varname' => 'Имя переменной',
            'field_size' => 'Размер поля',
            'field_size_min' => 'Минимальный размер поля',
            'default' => 'По умолчанию',
            'position' => 'Позиция',
            'required' => 'Обязательно',
        );

    }

}