<?php

namespace modules\catalog\models\import\provider;

class project extends \modules\catalog\models\import\Importer
{
    protected $providerName = "project";
    protected $imagePath = "upload/catalog/project";
    protected $remoteHost = "http://api2.gifts.ru/export/catalogue/";
    protected $remoteAuth = "691_xmlexport:WdyVFgou";
    private $_product_category = array();

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

        $this->treeWalk($arrXml['page']);
        $this->procProduct($arrXml['product']);

        $this->afterParse();
        return true;
    }

    public function treeWalk(&$categories)
    {
      if (!empty($categories['page'])){
          foreach($categories['page'] as $category)
          {
              $this->procCategory($category);
              
              if (!empty($category['page']))
                  $this->treechild($category);
          }
      }
    }

    public function treechild(&$categories, $level = 1)
    {
        if (isset($categories['page'][0]))
        {
            foreach($categories['page'] as $category)
            {
                $this->procCategory($category, $categories['page_id']);

                if (!empty($category['page']))
                    $this->treechild($category, $level + 1);
            }
        }else{
            $category = $categories['page'];
            $this->procCategory($category, $categories['page_id']);
        }
    }

    protected function procCategory(&$category, $id_parent = null)
    {
        $data = array(
            'title'     => $category['name'],
            'id_parent' => !empty($this->category_tree[$id_parent])?$this->category_tree[$id_parent]:$this->category_id,
        );

        $command = \Yii::app()->db->createCommand();

        // добавляем категорию
        if (empty($this->category_tree[$category['page_id']]))
        {
            $data['time_created'] = time();
            $command->insert($this->tableImportTree(), $data);

            $this->category_tree[$category['page_id']] = \Yii::app()->db->getLastInsertId();

            $command->insert($this->tableImportCategory(), array(
                'provider'          => 'project',
                'category_inner'    => $this->category_tree[$category['page_id']],
                'category_outter'   => $category['page_id']
            ));
        }
        // или обновляем
        else
        {
            $data['time_updated'] = time();
            $command->update($this->tableImportTree(), $data, 'id=:page_id', array(':page_id' => $this->category_tree[$category['page_id']]));
        }

        if (!empty($category['product']))
            foreach($category['product'] as $item)
                if (!empty($item['page']))
                    $this->_product_category[$item['product']] = $item['page'];

        $command->reset();

    }

    protected function procProduct(&$arrXml)
    {
        if (empty($arrXml))
            return;

        $command = \Yii::app()->db->createCommand();
        $appPath = \Yii::getPathOfAlias('application');

        foreach($arrXml as $product)
        {
            if (empty($product['product_id']))
                continue;

            $category    = !empty($this->_product_category[$product['product_id']])?$this->_product_category[$product['product_id']]:null;
            $id_tree     = !empty($this->category_tree[$category])?$this->category_tree[$category]:null;
            $id_category = !empty($this->category_schema[$id_tree])?$this->category_schema[$id_tree]:null;

            // image
            $preview    = isset($product['small_image']['@attributes']['src'])?$product['small_image']['@attributes']['src']:null;
            $image      = isset($product['big_image']['@attributes']['src'])?$product['big_image']['@attributes']['src']:null;
            $residue    = !empty($product['residue'])?intval($product['residue']):0;

            $images = array('image', 'preview');
            foreach($images as $varname)
            {
                $thumb = $$varname;
                $flag = false;
                $path = $appPath."/../".$this->imagePath."/".$thumb;

                if (!empty($thumb) && !file_exists($path))
                {
                    $flag = true;
                }
                elseif(!empty($thumb) && file_exists($path))
                {
                    list($width, $height, $type, $attr) = @getimagesize($path);
                    if (empty($width) || empty($height)){
                        $flag = true;
                    }else
                        $$varname = "http://".\Yii::app()->params['hostname']."/".$this->imagePath."/".$thumb;
                }

                if ($flag)
                {
                    $resource = curl_init();
                    sleep(1);
                    curl_setopt($resource, CURLOPT_URL, $this->remoteHost.$thumb);
                    curl_setopt($resource, CURLOPT_HEADER, 0);
                    curl_setopt($resource, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($resource, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($resource, CURLOPT_USERPWD, $this->remoteAuth);
                    $string = curl_exec($resource);
                    $http_status = curl_getinfo($resource, CURLINFO_HTTP_CODE);
                    curl_close($resource);

                    echo $http_status."\n";
                    
                    if ($http_status == 200){
                        echo $path."\n";
                        $file = fopen($path, "w+");
                        fwrite($file, $string);
                        fclose($file);
                        
                        list($width, $height, $type, $attr) = @getimagesize($path);
                        if (empty($width) || empty($height)){
                            $$varname = null;
                            unlink($path);
                        }else
                            $$varname = "http://".\Yii::app()->params['hostname']."/".$this->imagePath."/".$thumb;
                    }else
                        $$varname = null;
                }
            }


            $article = !empty($product['code'])?$product['code']:null;

            if (isset($product['price']) && is_array($product['price'])){
                $price = !empty($product['price']['price'])?floatval($product['price']['price']):0;
            }
            else{
                $price = !empty($product['price'])?floatval($product['price']):0;
            }

            // product insert data 
            $data_insert = array(
                'id_brand'      => !empty($product['brand'])?$this->getBrandId($product['brand']):null,
                'title'         => $product['name'],
                'article'       => $article,
                'preview'       => $preview,
                'image'         => $image,
                'price'         => $price,
                'notice'        => !empty($product['content'])?$product['content']:null,
                'content'       => !empty($product['content2'])?$product['content2']:null,
                'store'         => $residue,
                'enabled'       => 1,
                'time_created'  => time(),
            );

            // product update data 
            $data_update = array(
                'title'         => $product['name'],
                'article'       => $article,
                'preview'       => $preview,
                'image'         => $image,
                'price'         => $price,
                'notice'        => !empty($product['content'])?$product['content']:null,
                'content'       => !empty($product['content2'])?$product['content2']:null,
                'enabled'       => 1,
                'time_updated'  => time(),
            );

            if (empty($this->product_schema[$product['product_id']]))
            {
                $command->insert($this->product_table, $data_insert);

                $this->product_schema[$product['product_id']] = \Yii::app()->db->getLastInsertId();

                $command->insert($this->tableImportProduct(), array(
                    'provider'          => $this->providerName,
                    'product_inner'     => $this->product_schema[$product['product_id']],
                    'product_outter'    => $product['product_id']
                ));

            }
            else
            {
                if ($this->product_action=='update')
                {
                    $command->update($this->product_table, $data_update, 'id=:item_id', array(':item_id' => $this->product_schema[$product['product_id']]));
                }
                elseif($this->product_action=='disabled')
                {
                    $command->update($this->product_table, array('enabled'=>1), 'id=:item_id', array(':item_id' => $this->product_schema[$product['product_id']]));
                }
                else
                {
                    // $command->update($this->product_table, $data, 'id=:item_id', array(':item_id' => $this->product_schema[$product['product_id']]));
                }
            }
            $command->reset();

            $id_product = $this->product_schema[$product['product_id']];

            $this->category_product[$id_product][] = $id_category;
            $this->category_import[$id_product][] = $id_tree;

            // size
            if (!empty($product['product'])){
                foreach($product['product'] as $item)
                {
                    if (!empty($item['size_code']) && $id_size = $this->getAttribute("size", $item['size_code'], false))
                    {
                        // attribute size
                        $this->attribute_data['size']['data'][$id_product][] = array(
                            "id_size" => $id_size,
                            "article" => $item['code'],
                            "price" => !empty($item['price']['price'])?floatval($item['price']['price']):0
                        );

                        // [CUSTOM] attribute dictinary: store
                        /*
                        if (!empty($this->store_named['project'])){
                            $this->attribute_data['store']['data'][$id_product][] = array(
                                "id_store" => $this->store_named['project'],
                                "id_size" => $id_size,
                                "value" => $item['residue']
                            );
                        }
                        */
                    }
                }
            }

            // attribute list:
            $attrs = array(
                'matherial' => 'material'
            );

            foreach($attrs as $item => $attr)
                if (!empty($product[$item]) && !is_array($product[$item]))
                    $this->attribute_list[$id_product][$attr] = $product[$item];

        }

        return true;
    }

}