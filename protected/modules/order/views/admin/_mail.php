<?php

    $delivery_type = $model->values('delivery_type', 'title');

      $query_dictionary = Yii::app()->db->createCommand()
                ->select('id_field_size, code')
                ->from("{{mod_catalog_field_size}}")
                ->queryAll();

        $sizes = array();
        foreach ($query_dictionary as $item)
            $sizes[$item['id_field_size']] = $item['code'];

?>
<h3><?php echo Yii::t('order', 'Client information');?></h3>

<p>
<strong><?php echo Yii::t('order', 'Client name');?></strong>: <?php echo $model->person_name;?><br />
<strong><?php echo Yii::t('order', 'Client phone');?></strong>: <?php echo $model->contact_phone;?><br />
<strong><?php echo Yii::t('order', 'Client email');?></strong>: <?php echo $model->contact_email;?><br />
</p>

<p>
<strong><?php echo Yii::t('order', 'Delivery type');?></strong>: <?php echo $delivery_type[$model->delivery_type];?><br />
<strong><?php echo Yii::t('order', 'Delivery address');?></strong>: <?php echo $model->delivery_addr;?><br />
</p>

<p>
<strong><?php echo Yii::t('order', 'Comment');?></strong>: <?php echo $model->comment;?>
</p>

<h3><?php echo Yii::t('order', 'Product list');?></h3>
<table>
      <thead>
        <tr>
            <th><?php echo Yii::t('order', 'Goods');?></th>
            <th><?php echo Yii::t('order', 'Size');?></th>
            <th><?php echo Yii::t('order', 'Count');?></th>
            <th><?php echo Yii::t('order', 'Price');?></th>
            <th><?php echo Yii::t('order', 'Total price');?></th>
      </tr>
      </thead>
      <tbody>
<?php
      foreach($basket['product'] as $hash => $product):
            $data_product = $product['data'];
?>
            <tr>
                  <td>
                        <?php echo $data_product['title']?>
                  </td>
                  <td>
                        <?php echo (!empty($product['attributes']['size'])?$sizes[$product['attributes']['size']]:null)?>
                  </td>
                  <td>
                        <?php echo $product['count']?>
                  </td>
                  <td>
                        <?php echo $data_product['price']?>
                  </td>
                  <td>
                        <?php echo $product['cost'];?>
                  </td>
            </tr>
<?php  endforeach; ?>
      </tbody>
</table>

<p><?php echo Yii::t('order', 'Products in basket')?>: <?php echo $basket['total']['count']?></p>
<p><?php echo Yii::t('order', 'Total cost')?>: <?php echo $basket['total']['cost'];?><?php echo Yii::t('catalog', 'rub');?> </p>