<?php

namespace modules\catalog\models\import;

abstract class Importer
{
    /**
     * Имя провайдера данных
     */
    protected $providerName;

    /**
     * схема соответствия значений id выгрузки 
     * к внутреннему представлению для категорий
     */
    protected $category_tree = array();

    /**
     * схема соответствия значений id выгрузки 
     * к внутреннему представлению для продукции
     */
    protected $product_schema = array();


    protected $product_ids = array();

    /**
     * к каким категориям каталога была привязана продукция
     */
    protected $_category_product = array();

    /**
     * к каким категориям импорта была привязана продукция
     */
    protected $_category_import = array();

    /**
     * к каким категориям стала привязана продукция
     */
    protected $category_product = array();

    /**
     * к каким категориям стала привязана продукция
     */
    protected $category_import = array();

    /** 
     * Родительская категория по умолчанию
     */
    protected $category_id = null;

    /**
     * схема соответствия значений id выгрузки 
     * к внутреннему представлению для категорий
     */
    protected $category_schema;

    /**
     * таблица бд п каталога
     */
    protected $product_table;

    /**
     * значения атрибутов [словарей]
     */
    protected $attribute_data = array();

    /**
     * значения атрибутов
     */
    protected $attribute_list = array();
    
    protected $attributes = array();

    /**
     * список брэндов атрибутов
     */
    protected $brand_list = array();

    /**
     * именованный список складов
     */
    protected $store_named = array();

    protected $sql_step = 50;

    protected $product_action = null;

    public function __construct($config = array())
    {
        // переопределяем значения
        $list = array(
            'category_id',
        );

        foreach($list as $item)
            if (!empty($config[$item]))
                $this->$item = $config[$item];

        // получаем имена таблиц
        $this->category_table = \modules\catalog\models\Category::model()->tableName();
        $this->product_table  = \modules\catalog\models\Product::model()->tableName();
    }


    protected function tableImportCategory(){
        return "{{catalog_import_category}}";
    }

    protected function tableImportTree(){
        return "{{catalog_import_tree}}";
    }

    protected function tableImportProduct(){
        return "{{catalog_import_product}}";
    }

    protected function tableImportSchema(){
        return "{{catalog_import_schema}}";
    }

    protected function tableCategoryProduct(){
        return "{{catalog_category_product}}";
    }

    protected function tableCategoryImport(){
        return "{{catalog_import_category_product}}";
    }

    protected function tableProduct(){
        return "{{catalog_product}}";
    }

    protected function tableBrand(){
        return "{{catalog_brand}}";
    }

    protected function tableStore(){
        return "{{catalog_store}}";
    }

    protected function tableProductFields(){
        return "{{catalog_product_fields}}";
    }

    /**
     * Операции до обработки файла
     */
    protected function initSchema()
    {
        $provider = $this->providerName;

        if (empty($provider))
            return;

        // получаем схему соответствия категорий выгрузки и дерева импорта
        $query_category = \Yii::app()->db->createCommand()
                                    ->select('category_inner, category_outter')
                                    ->from($this->tableImportCategory())
                                    ->where('provider=:provider', array(':provider'=>$provider))
                                    ->queryAll();

        foreach($query_category as $item)
            $this->category_tree[$item['category_outter']] = $item['category_inner'];

        unset($query_category);

        // получаем схему соответствия продуктов каталога и продуктов выгрузки
        $query_product = \Yii::app()->db->createCommand()
                                   ->select('product_inner, product_outter')
                                   ->from($this->tableImportProduct())
                                   ->where('provider=:provider', array(':provider'=>$provider))
                                   ->queryAll();

        foreach($query_product as $item){
            $this->product_schema[$item['product_outter']] = $item['product_inner'];
            $this->product_ids[] = $item['product_inner'];
        }

        unset($query_product);

        // получаем схему соответствия категорий каталога и дерева импорта
        $query_schema = \Yii::app()->db->createCommand()
                                   ->select('id_tree, id_category')
                                   ->from($this->tableImportSchema())
                                   ->queryAll();

        foreach($query_schema as $item)
            $this->category_schema[$item['id_tree']] = $item['id_category'];

        unset($query_schema);

        // получаем схему соответствия категорий каталога и дерева импорта
        $query_brand = \Yii::app()->db->createCommand()
                                   ->select('id, title')
                                   ->from($this->tableBrand())
                                   ->queryAll();

        foreach($query_brand as $item)
            $this->brand_list[strtolower($item['title'])] = $item['id'];

        unset($query_brand);

        // получаем схему соответствия категорий каталога и дерева импорта
        $query_store = \Yii::app()->db->createCommand()
                                   ->select('id, slug')
                                   ->from($this->tableStore())
                                   ->queryAll();

        foreach($query_store as $item)
            $this->store_named[$item['slug']] = $item['id'];

        unset($query_store);

        // получаем схему соответствия категорий каталога и продукции
        $query_category = \Yii::app()->db->createCommand()
                                   ->select('id_category, id_product')
                                   ->from($this->tableCategoryProduct())
                                   ->queryAll();

        foreach($query_category as $item)
            $this->_category_product[$item['id_category']][$item['id_product']] = true;

        unset($query_category);

        // получаем схему соответствия категорий импорта каталога и продукции
        $query_category = \Yii::app()->db->createCommand()
                                   ->select('id_tree, id_product')
                                   ->from($this->tableCategoryImport())
                                   ->queryAll();

        foreach($query_category as $item)
            $this->_category_import[$item['id_tree']][$item['id_product']] = true;

        unset($query_category);

    }

    protected function unbindCategoryProduct($id_product)
    {
        unset($this->category_product[$id_product]);
    }
    
    protected function getBrandId($brand_title = null)
    {
    
        if (empty($brand_title) || is_array($brand_title))
            return;

        if (empty($this->brand_list[strtolower($brand_title)]))
        {
            \Yii::app()->db->createCommand()->insert($this->tableBrand(), array(
                'title' => $brand_title,
                'enabled' => 1,
            ));
            $this->brand_list[strtolower($brand_title)] = \Yii::app()->db->getLastInsertId();
        }

        return $this->brand_list[strtolower($brand_title)];
    }

    protected function bindCategoryProduct($id_product, $id_category)
    {
        if (empty($id_product) || empty($id_category))
            return;

        if (isset($this->product_schema[$id_product]))
            $id_product = $this->product_schema[$id_product];

        if (isset($this->category_tree[$id_category]))
            $id_category = $this->category_tree[$id_category];

        if (empty($id_product) || empty($id_category))
            return;

        if (!isset($this->category_product[$id_product][$id_category]))
            $this->category_product[$id_product][$id_category] = true;
    }

    /**
     * Действия перед добавление продукции
     */
    protected function beforeInsertItem($data_item){}

    /**
     * Действия после добавление продукции
     */
    protected function afterInsertItem($id_product, &$data_item){}

    /**
     * Действия после добавление продукции
     */
    abstract protected function afterParse();

    /**
     * Действия перед добавлением продукции
     */
    abstract protected function beforeParse();

    /**
     * Разбор файла
     */
    abstract protected function parseFile($fileName = null);

    public static function xmlObjectsIntoArray($arrObjData, $arrSkipIndices = array())
    {
        $arrData = array();
        
        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }
        
        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = self::xmlObjectsIntoArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }

    
    /**
     * Получение начальных данных об атрибутах
     */
    protected function initAttributeData()
    {
        $dictionaries = array("size", "color");
        foreach($dictionaries as $dictionary)
        {
            $query_dictionary = \Yii::app()->db->createCommand()
                                           ->select('id, code')
                                           ->from("{{catalog_field_".$dictionary."}}")
                                           ->queryAll();

            foreach($query_dictionary as $item)
                $this->attributes[$dictionary][strtolower($item['code'])] = $item['id'];
        }
    }

    /*
     * Получение указанного атрибута
     */
    protected function getAttribute($dictionary, $attribute, $flag_create = true)
    {
        $attribute = strtolower(trim($attribute));

        if (empty($attribute))
            return;

        if (!isset($this->attributes[$dictionary][$attribute]) && $flag_create)
        {
            \Yii::app()->db->createCommand()->insert("{{catalog_field_".$dictionary."}}", array(
                'code' => $attribute,
            ));
            $this->attributes[$dictionary][$attribute] = \Yii::app()->db->getLastInsertId();
        }

        if (isset($this->attributes[$dictionary][$attribute]))
            return $this->attributes[$dictionary][$attribute];
    }

    protected function procCategoryProduct()
    {
        $pairs = array();
        foreach($this->category_product as $product => $categories)
            foreach($categories as $category)
                if (!empty($category) && !empty($product) && !isset($this->_category_product[$category][$product]))
                    $pairs[] = "(".$category.", ".$product.")";

        $pairs_chunk = array_chunk($pairs, 200);
        foreach($pairs_chunk as $chunk){
            \Yii::app()
                ->db
                ->createCommand("INSERT INTO ".$this->tableCategoryProduct()." (`id_category`,`id_product`) VALUES ".implode(",", $chunk).";")
                ->execute();
        }

        $pairs = array();
        foreach($this->category_import as $product => $categories)
            foreach($categories as $category)
                if (!empty($category) && !empty($product) && !isset($this->_category_import[$category][$product]))
                    $pairs[] = "(".$category.", ".$product.")";

        $pairs_chunk = array_chunk($pairs, 200);
        foreach($pairs_chunk as $chunk){
            \Yii::app()
                ->db
                ->createCommand("INSERT INTO ".$this->tableCategoryImport()." (`id_tree`,`id_product`) VALUES ".implode(",", $chunk).";")
                ->execute();
        }
    }

    /**
     * Обработка значений атрибутов
     */
    protected function procAttributes()
    {
        foreach($this->attribute_data as $dictionary => $params)
        {
            $list = !empty($params['data'])?$params['data']:null;
            $where = !empty($params['where'])?$params['where']:null;

            if (!empty($list))
            {
                // очищаем старые значения
                $ids_list = array_chunk(array_keys($list), 200);
                foreach($ids_list as $ids){
                    $ids = array_map(function($id){ return "'".intval($id)."'"; }, $ids);
                    $condition = "id_product IN(".implode(",", $ids).")";
                    if (!empty($where)){
                        $condition .=" AND ".$where;
                    }
                    \Yii::app()->db->createCommand()->delete("{{catalog_product_".$dictionary."}}", $condition);
                }

                // добавляем новые
                foreach($list as $id_product => $values)
                {
                    if (in_array($id_product, $this->product_ids))
                        foreach($values as $value){
                            $value['id_product'] = $id_product;
                            \Yii::app()->db->createCommand()->insert("{{catalog_product_".$dictionary."}}", $value);
                        }
                }
            }
        }

        foreach($this->attribute_list as $id_product => $values)
        {
            if (in_array($id_product, $this->product_ids))
            {
                \Yii::app()->db->createCommand()->update(
                    $this->tableProductFields(), 
                    $values, 
                    'id_product=:id_product', 
                    array(':id_product'=>$id_product)
                );
            }
            else
            {
                $values['id_product'] = $id_product;
                \Yii::app()->db->createCommand()->insert($this->tableProductFields(), $values);
            }
        }
    }
}