<?php

namespace modules\catalog\models\import\provider;

class group7 extends \modules\catalog\models\import\Importer
{
    protected $providerName = "group7";
    protected $article_schema = array();

    protected function beforeParse()
    {
        \Yii::app()->db->enableProfiling = false;
        \Yii::app()->db->enableParamLogging = false;
        $this->initSchema();
        $this->initAttributeData();
        $this->initArticleList();
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

    protected function initArticleList()
    {
        $rows = \Yii::app()->db->createCommand()
                ->select('p.article, i.product_outter')
                ->from($this->tableProduct().' p')
                ->join($this->tableImportProduct(). ' i', 'i.product_inner=p.id')
                ->where("provider = 'group7'")
                ->queryAll();

        foreach($rows as $row)
            if (!empty($row['article']))
                $this->article_schema[$row['article']] = $row['product_outter'];

        unset($rows);

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
        $this->procCategory($arrXml);
        $this->procProduct($arrXml);

        $this->afterParse();

        return true;
    }

    /**
     * Обработка категорий
     */
    protected function procCategory(&$arrXml)
    {
        if (empty($arrXml['categories']['Item']))
            return;

        $transaction = \Yii::app()->db->beginTransaction();
        try
        {
            $command = \Yii::app()->db->createCommand('ALTER TABLE '.$this->tableImportTree().' DISABLE KEYS');
            $command->reset();

            foreach($arrXml['categories']['Item'] as $category)
            {
                if (empty($category['item_id']) || empty($category['title']))
                    continue;

                // работаем с родительской категорией
                if (empty($category['parent_id']))
                    $category['parent_id'] = $this->category_id;
                else
                {
                    if (empty($this->category_tree[$category['parent_id']]))
                    {
                        $command->insert($this->tableImportTree(), array(
                            'title'         => 'unknown',
                            'id_parent'     => $this->category_id,
                            'time_created'  => time()
                        ));
                        $command->reset();

                        $this->category_tree[$category['parent_id']] = \Yii::app()->db->getLastInsertId();

                        $command->insert($this->tableImportTree(), array(
                            'provider'          => $this->providerName,
                            'category_inner'    => $this->category_tree[$category['parent_id']],
                            'category_outter'   => $category['parent_id']
                        ));
                    }
                }

                $data = array(
                    'title'     => $category['title'],
                    'id_parent' => !empty($this->category_tree[$category['parent_id']])?$this->category_tree[$category['parent_id']]:$this->category_id,
                );

                // создаем категорию
                if (empty($this->category_tree[$category['item_id']]))
                {
                    $data['time_created'] = time();
                    $command->insert($this->tableImportTree(), $data);
                    $command->reset();

                    $this->category_tree[$category['item_id']] = \Yii::app()->db->getLastInsertId();

                    $command->insert($this->tableImportCategory(), array(
                        'provider'          => $this->providerName,
                        'category_inner'    => $this->category_tree[$category['item_id']],
                        'category_outter'   => $category['item_id']
                    ));

                }
                // или обновляем
                else
                {
                    $data['time_updated'] = time();
                    $command->update($this->tableImportTree(), $data, 'id=:item_id', array(':item_id' => $this->category_tree[$category['item_id']]));
                }

                $command->reset();
            }

            $command = \Yii::app()->db->createCommand('ALTER TABLE '.$this->tableImportTree().' ENABLE KEYS');
            $transaction->commit();
        }
        catch (Exception $e){
            $transaction->rollBack();
        }

        return true;
    }

    /**
     * Обработка продукции
     */
    protected function procProduct(&$arrXml)
    {
        if (empty($arrXml['entries']['Item']))
            return;

        $command = \Yii::app()->db->createCommand();
        $i=0;
        foreach($arrXml['entries']['Item'] as $product)
        {
            if (empty($product['item_id']))
                continue;

            if (!empty($product['id_parent'])){
                if (!is_array($id_parent))
                    $category_title = $id_parent;
                else
                    $category_title = $id_parent[0];
            }
            else
                $category_title = null;

            $id_tree     = !empty($this->category_tree[$category_title])?$this->category_tree[$category_title]:null;
            $id_category = !empty($this->category_schema[$id_tree])?$this->category_schema[$id_tree]:null;

            if (empty($product['title']))
                continue;

            $size = null;
            
            if (!empty($product['article'])){
                preg_match('/([0-9a-z]{7})((\s)?(2X)?[xlmsp]{1,5})/i', $product['article'], $matches);

                if (!empty($matches[1]) && !empty($matches[2])){

                    $product['article'] = $matches[1];
                    $size = $matches[2];

                    if ($last = strripos($product['title'], $size))
                        $product['title'] = substr($product['title'], 0, $last);

                    if (isset($this->article_schema[$product['article']]))
                        $product['item_id'] = $this->article_schema[$product['article']];
                    else
                        $this->article_schema[$product['article']] = $product['item_id'];
                }
            }

            // product insert data
            $data_insert = array(
                'title'         => $product['title'],
                'article'       => !empty($product['article'])?$product['article']:null,
                'price'         => !empty($product['cost'])?floatval($product['cost']):0,
                'store'         => !empty($product['residue'])?intval($product['residue']):0,
                'time_created'  => time(),
                'enabled'       => 1
            );

            // product update data:
            $data_update = array(
                'title'         => $product['name'],
                'article'       => $article,
                'preview'       => $preview,
                'image'         => $image,
                'price'         => $price,
                'enabled'       => 1,
                'time_updated'  => time(),
            );

            if (empty($this->product_schema[$product['item_id']]))
            {
                $command->insert($this->product_table, $data_insert);
                $command->reset();

                $this->product_schema[$product['item_id']] = \Yii::app()->db->getLastInsertId();

                $command->insert($this->tableImportProduct(), array(
                    'provider'          => $this->providerName,
                    'product_inner'     => $this->product_schema[$product['item_id']],
                    'product_outter'    => $product['item_id']
                ));
            }
            else
            {
                if ($this->product_action=='update'){
                    $command->update($this->product_table, $data_update, 'id=:item_id', array(':item_id' => $this->product_schema[$product['item_id']]));
                }
                elseif($this->product_action=='disabled'){
                    $command->update($this->product_table, array('enabled'=>1), 'id=:item_id', array(':item_id' => $this->product_schema[$product['item_id']]));
                }
                else{
                    // $command->update($this->product_table, $data, 'id=:item_id', array(':item_id' => $this->product_schema[$product['item_id']]));
                }
            }

            $id_product = $this->product_schema[$product['item_id']];
            
            $this->category_product[$id_product][] = $id_category;
            $this->category_import[$id_product][] = $id_tree;


            if (!empty($size))
                $this->attribute_data['size']['data'][$id_product][] = array(
                    "id_size" => $this->getAttribute("size", $size)
                );

            $command->reset();

        }

        return true;
    }

}