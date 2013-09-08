<?php

namespace modules\catalog\models\import;

class Form extends \CFormModel
{
    public $data_file;
    public $data_provider;
    public $category_id;
    public $product_action;

    public function values($setting = null, $value = null)
    {
        $settings = array(
            "data_provider" => array(
                "default" => "group7",
                "data" => array(
                    "group7" => "Группа 7 / продукция",
                    "project" => "Проект 111 / продукция",
                    "projectstore" => "Проект 111 / остатки в Красноярске",
                    "projectresidue" => "Проект 111 / остатки поставщика",
                    "oasis" => "Оазис / продукция",
                    "oasisstore" => "Оазис / остатки в Красноярске",
                    "oasisresidue" => "Оазис / остатки поставщика",
                    "oceanwares" => "Океан / продукция",
                    "oceanfiles" => "Океан / изображения",
                    "oceanstore" => "Океан / остатки в Красноярске",
                    
                ),
            ),
            "product_action" => array(
                "default" => "none",
                "data" => array(
                    "none" => "Ничего",
                    "update" => "Обновить",
                    "disable" => "Деактивировать"
                ),
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    public function rules()
    {
        return array(
            array('category_id', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
            array('data_provider, data_file', 'required'),
            array('data_provider', 'in', 'range' => array_keys($this->values('data_provider', 'data')), 'allowEmpty'=>false),
            array('product_action', 'in', 'range' => array_keys($this->values('product_action', 'data')), 'allowEmpty'=>true),
            array('data_file', 'file', 'allowEmpty'=>false),
            array('data_file', 'safe')
        );
    }

    public function attributeLabels()
    {
        return array(
            'data_file' => 'Файл импорта',
            'data_provider' => 'Поставщик',
            'category_id' => 'Раздел каталога',
            'product_action' => 'Действие над пересекающейся продукцией',
        );
    }

}
