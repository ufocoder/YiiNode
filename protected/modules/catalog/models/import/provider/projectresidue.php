<?php

namespace modules\catalog\models\import\provider;

class projectresidue extends \modules\catalog\models\import\Importer
{
    protected $providerName = "project";
    protected $product_size_schema = array();
    
    protected function beforeParse()
    {
        \Yii::app()->db->enableProfiling = false;
        \Yii::app()->db->enableParamLogging = false;
        $this->initSchema();
        $this->initArticleSize();
    }

    protected function afterParse()
    {
        $this->procAttributes();
        \modules\catalog\models\Category::model()->updateStoreCount($this->providerName);
        \Yii::app()->db->enableProfiling = YII_DEBUG;
        \Yii::app()->db->enableParamLogging = YII_DEBUG;
    }

    protected function initArticleSize(){
        // получаем схему соответствия продуктов каталога и продуктов выгрузки
        $query_product = \Yii::app()->db->createCommand()
                                   ->select('s.article, s.id_size, s.id_product')
                                   ->from($this->tableImportProduct()." p")
                                   ->join('{{catalog_product_size}} s', '`s`.`id_product`=`p`.`product_inner`')
                                   ->where('p.provider=:provider', array(':provider'=>'project'))
                                   ->queryAll();

                                   
        foreach($query_product as $item)
            $this->product_size_schema[$item['article']] = array(
                'id_size' => $item['id_size'],
                'id_product' => $item['id_product']
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

        if (empty($arrXml['stock']))
            return;

        try{
            foreach($arrXml['stock'] as $item)
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

        if (isset($this->product_schema[$product['code']]))
            \Yii::app()->db->createCommand()->update("{{catalog_product}}", array('store'=>$product['amount']), 'id=:product_id', array(':product_id' => $this->product_schema[$product['code']]));
        elseif (isset($this->product_size_schema[$product['code']]) && $this->store_named['project'])
        {
            $schema = $this->product_size_schema[$product['code']];
            $params = array(
                ':product_id' => $schema['id_product'],
                ':size_id' => $schema['id_size'],
                ':store_id' => $this->store_named['project'],
            );

            if (!\Yii::app()->db->createCommand()->update("{{catalog_product_store}}", 
                array('value'=>$product['amount']),
                'id_product=:product_id AND id_size=:size_id AND id_store=:store_id', 
                $params)
            ){
                \Yii::app()->db->createCommand()->insert("{{catalog_product_store}}", array(
                    'id_product' => $schema['id_product'],
                    'id_size' => $schema['id_size'],
                    'id_store' => $this->store_named['project'],
                    'value' => $product['amount']
                ));
            }
        }

        return true;
    }
}