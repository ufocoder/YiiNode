<?php

namespace modules\catalog\models\import\provider;

class oasis extends \modules\catalog\models\import\Importer
{
    protected $providerName = "oasis";
    protected $imagePath = "http://pic.krukro.com/public_html/supermarket/products/";

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
        \modules\catalog\models\Category::model()->updateProductCount();
        \modules\catalog\models\Category::model()->updateStoreCount($this->providerName);

        \Yii::app()->db
            ->createCommand("UPDATE {{catalog_product}} `t` SET `enabled`=0 WHERE ( SELECT `id_category` FROM {{catalog_category_product}} `c` WHERE `t`.`id` = c.id_product LIMIT 1) IS NULL")
            ->execute();
            
        \Yii::app()->db
            ->createCommand("UPDATE {{catalog_product}} `t` SET `enabled`=1 WHERE ( SELECT `id_category` FROM {{catalog_category_product}} `c` WHERE `t`.`id` = c.id_product LIMIT 1) IS NULL")
            ->execute();

        \modules\catalog\models\Category::model()->updateBrandCount();

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
        if (empty($arrXml['item']))
            return;

        $transaction = \Yii::app()->db->beginTransaction();
        try{
            $command = \Yii::app()->db->createCommand();
            foreach($arrXml['item'] as $product)
            {

                if (is_array($product['tov_rubr']['rubr']))
                {
                    foreach($product['tov_rubr']['rubr'] as $category)
                        $this->procCategory($category);
                }
                else
                {
                    $category = $product['tov_rubr']['rubr'];
                    $this->procCategory($category);
                }

                $this->procProduct($product);
                $command->reset();
            }
            $transaction->commit(); 
        }
        catch (Exception $e){
            $transaction->rollBack();
        }
        
        return true;
    }
    
    protected function procCategory($category)
    {
        if (!isset($this->category_tree[$category]))
        {
            $items = explode("\\", $category);
            $path = '';

            $command = \Yii::app()->db->createCommand();
            foreach($items as $item)
            {
                if (empty($item))
                    break;

                $data = array(
                    'title'     => $item,
                    'id_parent' => empty($this->category_tree[$path])?$this->category_id:$this->category_tree[$path],
                );

                $path .= $item.'\\';

                if (empty($this->category_tree[$path]))
                {
                    $data['time_created']   = time();
                    $command->insert($this->tableImportTree(), $data);
                    
                    $this->category_tree[$path] = \Yii::app()->db->getLastInsertId();

                    $command->insert($this->tableImportCategory(), array(
                        'provider'          => 'oasis',
                        'category_inner'    => $this->category_tree[$path],
                        'category_outter'   => $path
                    ));

                }
                else
                {
                    $data['time_updated'] = time();
                    $command->update($this->tableImportTree(), $data, 'id=:path', array(':path' => $this->category_tree[$path]));
                }
            }
        }
    }

    public function procProduct(&$product)
    {   
    
        if (!is_array($product['tov_rubr']['rubr']))
            $category_title = $product['tov_rubr']['rubr'];
        else
            $category_title = null;

        $id_tree     = !empty($this->category_tree[$category_title])?$this->category_tree[$category_title]:null;
        $id_category = !empty($this->category_schema[$id_tree])?$this->category_schema[$id_tree]:null;

        $size        = null;

        // preview:
        if (isset($product['tov_pic']['small']['pic'])){
            if (is_array($product['tov_pic']['small']['pic']))
                $preview = $this->imagePath.'small/'.$product['tov_pic']['small']['pic'][0];
            else
                $preview = $this->imagePath.'small/'.$product['tov_pic']['small']['pic'];
        }
        else{
            $preview = null;
        }

        // image:
        if (isset($product['tov_pic']['big']['pic'])){
            if (is_array($product['tov_pic']['big']['pic']))
                $image = $this->imagePath.'big/'.$product['tov_pic']['big']['pic'][0];
            else
                $image = $this->imagePath.'big/'.$product['tov_pic']['big']['pic'];
        }
        else{
            $image = null;
        }

        // article [MERGE PRODUCT]:
        if (preg_match("/^([0-9a-zA-Z]{7})([2-4]XL|[a-zA-Z]+)$/i", $product['tov_id'], $matches))
        {
            if (!empty($matches[2])){
                $size = $matches[2];
                $article = $matches[1];
            }else
                $article = $matches[0];
        }
        else
            $article = $product['tov_id'];

        $residue = intval($product['tov_kol']);

        // product insert data:
        $data_insert = array(
            'id_brand'      => !empty($product['tov_brand'])?$this->getBrandId($product['tov_brand']):null,
            'article'       => $article,
            'title'         => $product['tov_name'],
            'price'         => floatval($product['tov_price']),
            'preview'       => $preview,
            'image'         => $image,
            'store'         => $residue,
            'content'       => (!empty($product['tov_opis']) && !is_array($product['tov_opis']))?$product['tov_opis']:null,
            'enabled'       => 1,
            'time_created'  => time()
        );

        // product update data:
        $data_update = array(
            'id_brand'      => !empty($product['tov_brand'])?$this->getBrandId($product['tov_brand']):null,
            'title'         => $product['tov_name'],
            'article'       => $article,
            'preview'       => $preview,
            'image'         => $image,
            'price'         => floatval($product['tov_price']),
            'enabled'       => 1,
            'time_updated'  => time(),
        );

        $command = \Yii::app()->db->createCommand();

        // product
        if (empty($this->product_schema[$article]))
        {
            $command->insert($this->product_table, $data_insert);
            $command->reset();

            $insertedId = \Yii::app()->db->getLastInsertId();
            $this->product_schema[$article] = $insertedId;
            $this->product_ids[] = $insertedId;

            $command->insert($this->tableImportProduct(), array(
                'provider'          => $this->providerName,
                'product_inner'     => $this->product_schema[$article],
                'product_outter'    => $article
            ));
        }
        else
        {
            if ($this->product_action=='update')
            {
                $command->update($this->product_table, $data_update, 'id=:tov_id', array(':tov_id' => $this->product_schema[$article]));
            }
            elseif($this->product_action=='disabled')
            {
                $command->update($this->product_table, array('enabled'=>1), 'id=:tov_id', array(':tov_id' => $this->product_schema[$article]));
            }
            else{
                // $command->update($this->product_table, $data, 'id=:tov_id', array(':tov_id' => $this->product_schema[$article]));
            }
        }

        $command->reset();

        $id_product = $this->product_schema[$article];

        $this->category_product[$id_product][] = $id_category;
        $this->category_import[$id_product][] = $id_tree;

        // [CUSTOM] attribute dictinary: store
        if (!empty($this->store_named['oasis']) && !empty($size)){
            $this->attribute_data['store']['data'][$id_product][] = array(
                "id_store" => $this->store_named['oasis'],
                "id_size" => $this->getAttribute("size", $size),
                "value" => $residue
            );
        }

        // attribute dictionary: size
        if (!empty($size)){
            $this->attribute_data['size']['data'][$id_product][] = array(
                "id_size" => $this->getAttribute("size", $size),
            );
        }

        // attribute list:
        $attrs = array(
            'tov_razm' => 'proportions',
            'tov_vesed' => 'weight',
            'tov_mat' => 'material',
            'tov_upak_vid' => 'package'
        );
        
        foreach($attrs as $item => $attr)
            if (!empty($product[$item]) && !is_array($product[$item]))
                $this->attribute_list[$id_product][$attr] = $product[$item];

        return true;
    }
}