<?php

namespace modules\catalog\models\import;

class ImporterStore extends \modules\catalog\models\import\Importer
{
    protected $providerName = "default";
    protected $storeName = "krasnoyarsk";

    protected $productArticleSize = array();

    protected function beforeParse()
    {
        \Yii::app()->db->enableProfiling = false;
        \Yii::app()->db->enableParamLogging = false;
        $this->initSchema();
        $this->initArticleList();
        $this->initAttributeData();
    }
    
    protected function initArticleList()
    {
        // получаем схему соответствия продуктов каталога и продуктов выгрузки
        $query_product = \Yii::app()->db->createCommand()
                                   ->select('ip.product_outter, ps.article, ps.id_size')
                                   ->from($this->tableImportProduct().' ip')
                                   ->join('{{catalog_product_size}} ps', 'ip.product_inner = ps.id_product')
                                   ->where('provider=:provider', array(':provider' => $this->providerName))
                                   ->queryAll();

        foreach($query_product as $item)
            if (!empty($item['article']))
                $this->productArticleSize[$item['article']] = array(
                  "id_product" => $item['product_outter'],
                  "id_size" => $item['id_size']
                );

    }

    protected function afterParse()
    {
        $this->procAttributes();
        \modules\catalog\models\Category::model()->updateStoreCount($this->providerName);
        
        \Yii::app()->db->enableProfiling = YII_DEBUG;
        \Yii::app()->db->enableParamLogging = YII_DEBUG;
    }

    protected function procArticle($article)
    {
        $id_product = null;
        $id_size = null;

        if (!empty($this->product_schema[$article])){
            $id_product = $this->product_schema[$article];
        }
        elseif (!empty($this->productArticleSize[$article])){
            $id_product = $this->productArticleSize[$article]['id_product'];
            $id_size = $this->productArticleSize[$article]['id_size'];
        }

        return array(
            'id_product' => $id_product,
            'id_size' => $id_size,
        );
    }

    /*
     * Разбор файла
     */
    public function parseFile($xmlUrl = null)
    {
        if (empty($xmlUrl) || !file_exists($xmlUrl))
            return false;

        $this->beforeParse();

        $xmlStr = file_get_contents($xmlUrl);
        $xmlObj = simplexml_load_string($xmlStr);
        $arrXml = self::xmlObjectsIntoArray($xmlObj);

        $this->procList($arrXml);
        $this->afterParse();

        return true;
    }
    
    protected function procList(&$arrXml)
    {
        $transaction = \Yii::app()->db->beginTransaction();

        if (empty($arrXml['entries']['Item']))
            return;

        try{
            foreach($arrXml['entries']['Item'] as $item)
                $this->procProduct($item);

        }
        catch (Exception $e){
            $transaction->rollBack();
        }

        $transaction->commit(); 

        return true;
    }

    public function procProduct($product)
    {
        if (empty($product))
            return;

        if (empty($product['article']))
            return;

        $id_product = null;
        $id_size = null;

        $attr_product = $this->procArticle($product['article']);

        $id_product = $attr_product['id_product'];
        $id_size = $attr_product['id_size'];

        if (!empty($id_product) && !empty($this->store_named[$this->storeName])){

            $this->attribute_data['store']['where'] = 'id_store = '.$this->store_named[$this->storeName]['id'];
            $this->attribute_data['store']['data'][$id_product][] = array(
                "id_store" => $this->store_named[$this->storeName]['id'],
                "id_size" => $id_size,
                "value" => $product['residue']
            );

        }

        return true;
    }
}