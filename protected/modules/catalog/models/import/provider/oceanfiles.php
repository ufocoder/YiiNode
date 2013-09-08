<?php

namespace modules\catalog\models\import\provider;

class oceanfiles extends \modules\catalog\models\import\Importer
{
    protected $providerName = "ocean";

    protected function beforeParse()
    {
        \Yii::app()->db->enableProfiling = false;
        \Yii::app()->db->enableParamLogging = false;
        $this->initSchema();
    }

    protected function afterParse()
    {
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

        if (!isset($arrXml['host']['name']))
            return false;
        else
            $hostname = $arrXml['host']['name'];

        try{
            foreach($arrXml['item'] as $item)
                $this->procProduct($item, $hostname);

        }
        catch (Exception $e){
            $transaction->rollBack();
        }

        $transaction->commit(); 

        return true;
    }

    public function procProduct($product, $hostname)
    {
        if (empty($product))
            return;

        if (!isset($product['id']))
            return;

        if (empty($this->product_schema[$product['id']]))
            return;

        $data = array(
            'preview'   => $hostname.$product['@attributes']['path'].$product['file'][1]['@attributes']['name'],
            'image'     => $hostname.$product['@attributes']['path'].$product['file'][1]['@attributes']['name']
        );

        $command = \Yii::app()->db->createCommand();
        $command->update($this->product_table, $data, 'id=:tov_id', array(':tov_id' => $this->product_schema[$product['id']]));
        
        return true;
    }
}