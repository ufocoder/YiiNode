<?php

namespace modules\catalog\models\import\provider;

class oceanwares extends \modules\catalog\models\import\Importer
{
    protected $providerName = "ocean";

    protected function beforeParse()
    {
        \Yii::app()->db->enableProfiling = false;
        \Yii::app()->db->enableParamLogging = false;
        $this->initSchema();
        $this->initAttributeData();
    }

    protected function afterParse()
    {
        $this->procCategoryProduct();
        $this->procAttributes();
        \modules\catalog\models\ImportTree::model()->buildNestedSet();
        \modules\catalog\models\Category::model()->updateBrandCount();
        \modules\catalog\models\Category::model()->updateProductCount();
        \modules\catalog\models\Category::model()->updateStoreCount($this->providerName);

        \Yii::app()->db
            ->createCommand("UPDATE {{catalog_product}} `t` SET `enabled`=0 WHERE ( SELECT `id_category` FROM {{catalog_category_product}} `c` WHERE `t`.`id` = c.id_product LIMIT 1) IS NULL")
            ->execute();
            
        \Yii::app()->db
            ->createCommand("UPDATE {{catalog_product}} `t` SET `enabled`=1 WHERE ( SELECT `id_category` FROM {{catalog_category_product}} `c` WHERE `t`.`id` = c.id_product LIMIT 1) IS NULL")
            ->execute();

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

        unset($arrXml);
        
        $this->afterParse();

        return true;
    }
    
    protected function procList(&$arrXml)
    {
        if (empty($arrXml['group']))
            return;

        foreach($arrXml['group'] as $group)
        {
            $this->procCategory($group['name']);
            $this->procProduct($group['item'], $group['name']);

            if (isset($group['subgroup']))
                foreach($group['subgroup'] as $subgroup)
                {
                    $this->procCategory($subgroup['name'], $group['name']);
                    $this->procProduct($subgroup['item'], $subgroup['name']);
                }
        }

        return true;
    }
    
    protected function procCategory($category, $parent = null)
    {
        $data = array(
            'title'     => $category,
            'id_parent' => !empty($this->category_tree[$parent])?$this->category_tree[$parent]:$this->category_id
        );

        $command = \Yii::app()->db->createCommand();

        if (empty($this->category_tree[$category]))
        {
            $data['time_created'] = time();

            $command->insert($this->tableImportTree(), $data);

            $this->category_tree[$category] = \Yii::app()->db->getLastInsertId();

            $command->insert($this->tableImportCategory(), array(
                'provider'          => 'ocean',
                'category_inner'    => $this->category_tree[$category],
                'category_outter'   => $category
            ));
        }
        else
        {
            $data['time_updated'] = time();
            $command->update($this->tableImportTree(), $data, 'id=:path', array(':path' => $this->category_tree[$category]));
        }
    }

    public function procProduct(&$arrXml, $category_title)
    {

        if (empty($arrXml))
            return;

        $transaction = \Yii::app()->db->beginTransaction();

        $id_tree     = !empty($this->category_tree[$category_title])?$this->category_tree[$category_title]:null;
        $id_category = !empty($this->category_schema[$id_tree])?$this->category_schema[$id_tree]:null;

        try{
            $command = \Yii::app()->db->createCommand();
            $index = 1;

            foreach($arrXml as $product)
            {
                $index++;

                // article:
                $article = $product['id'];

                // product insert data:
                $data_insert = array(
                    'article'       => $article,
                    'title'         => $product['name'],
                    'price'         => !empty($product['@attributes']['price'])?floatval($product['@attributes']['price']):0,
                    'total'         => !empty($product['@attributes']['balance'])?floatval($product['@attributes']['balance']):0,
                    'enabled'       => 1,
                    'time_created'  => time(),
                );

                // product update data:
                $data_update = array(
                    'title'         => $product['name'],
                    'article'       => $article,
                    'price'         => !empty($product['@attributes']['price'])?floatval($product['@attributes']['price']):0,
                    'total'         => !empty($product['@attributes']['balance'])?floatval($product['@attributes']['balance']):0,
                    'enabled'       => 1,
                    'time_updated'  => time(),
                );
                
                // product
                if (empty($this->product_schema[$article]))
                {
                    $command->insert($this->product_table, $data_insert);
                    $command->reset();

                    $this->product_schema[$product['id']] = \Yii::app()->db->getLastInsertId();

                    $command->insert($this->tableImportProduct(), array(
                        'provider'          => $this->providerName,
                        'product_inner'     => $this->product_schema[$article],
                        'product_outter'    => $product['id']
                    ));
                }
                else
                {
                    if ($this->product_action=='update')
                    {
                        $command->update($this->product_table, $data_update, 'id=:item_id', array(':item_id' => $this->product_schema[$article]));
                    }
                    elseif($this->product_action=='disabled')
                    {
                        $command->update($this->product_table, array('enabled'=>1), 'id=:item_id', array(':item_id' => $this->product_schema[$article]));
                    }
                    else{
                        // $command->update($this->product_table, $data, 'id=:item_id', array(':item_id' => $this->product_schema[$article]));
                    }
                }

                $id_product = $this->product_schema[$article];

                $this->category_product[$id_product][] = $id_category;
                $this->category_import[$id_product][] = $id_tree;

                // [CUSTOM] attribute dictinary: store 
                if (!empty($this->store_named['ocean'])){
                    $this->attribute_data['store']['data'][$id_product][] = array(
                        "id_store" => $this->store_named['ocean'],
                        "value" => $product['@attributes']['balance']
                    );
                }

                // attibutes: size
                if (!empty($product['textiles'])){
                    foreach($product['textiles']['textitem'] as $textitem){
                        if (!empty($textitem['@attributes']['size']))
                            $this->attribute_data['size']['data'][$id_product][] = array(
                                "id_size" => $this->getAttribute("size", $textitem['@attributes']['size']),
                            );
                    }
                }

                // attribute list:
                $attrs = array(
                    'size' => 'proportions',
                    'weight' => 'weight',
                    'material' => 'material'
                );

                foreach($attrs as $item => $attr)
                    if (!empty($product[$item]) && !is_array($product[$item]))
                        $this->attribute_list[$id_product][$attr] = $product[$item];
            }
        }
        catch (Exception $e){
            $transaction->rollBack();
        }

        $transaction->commit(); 

        return true;

    }
}