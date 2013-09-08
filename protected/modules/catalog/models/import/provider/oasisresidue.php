<?php

namespace modules\catalog\models\import\provider;

class oasisResidue extends \modules\catalog\models\import\Importer
{
    protected $providerName = "oasis";

    protected function beforeParse()
    {
        \Yii::app()->db->enableProfiling = false;
        \Yii::app()->db->enableParamLogging = false;
        $this->initSchema();
        $this->initAttributeData();
    }

    protected function afterParse()
    {
        $this->procAttributes();
        \modules\catalog\models\Category::model()->updateStoreCount($this->providerName);
        \Yii::app()->db->enableProfiling = YII_DEBUG;
        \Yii::app()->db->enableParamLogging = YII_DEBUG;
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

        if (empty($arrXml['item']))
            return;

        try{
            foreach($arrXml['item'] as $item)
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

        if (!isset($product['tov_id']))
            return;

        // article [MERGE PRODUCT]:
        $size = null;
        if (preg_match("/([2-4]XL|[^0-9]+)$/i", $product['tov_id'], $matches))
        {
            $size = $matches[0];
            $article = substr($product['tov_id'], 0, strlen($product['tov_id'])-strlen($size));
        }
        else
            $article = $product['tov_id'];

        if (empty($this->product_schema[$article]))
            return;

        $id_product = $this->product_schema[$article];

        if (!empty($this->store_named['oasis']))
        {
            $this->attribute_data['store']['where'] = 'id_store = '.$this->store_named['oasis'];
            $this->attribute_data['store']['data'][$id_product][] = array(
                "id_store" => $this->store_named['oasis'],
                "id_size" => !empty($size)?$this->getAttribute("size", $size):null,
                "value" => !empty($product['tov_kol'])?$product['tov_kol']:0
            );
        }

        return true;
    }
}