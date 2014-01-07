<?php
    $this->pageTitle = Yii::t('order', 'Basket');
    $this->breadcrumbs = array(
        Yii::t('order', 'Ordering')
    );

    if (!empty($basket['product'])):
?>
            <div class="basket-list">
              <table>
                  <thead>
                    <tr>
                        <th class="col-1"><?php echo Yii::t('order', 'Photo');?></th>
                        <th class="col-2"><?php echo Yii::t('order', 'Goods');?></th>
                        <th class="col-4"><?php echo Yii::t('order', 'Count');?></th>
                        <th class="col-5"><?php echo Yii::t('order', 'Price');?></th>
                        <th class="col-6"><?php echo Yii::t('order', 'Total price');?></th>
                        <th class="col-7"></th>
                  </tr>
                  </thead>
                  <tbody>
<?php  foreach($basket['product'] as $hash => $product):
        $id_product = $product['id'];
        $data_product = $product['data'];
?>
                  <tr id="basket-product-<?php echo $hash;?>">
                    <td>
                        <div class="product-image">
<?php      if ($data_product['preview']): ?>
                        <a href="<?php echo $data_product['preview']?>" title="<?php echo CHtml::encode($data_product['title'])?>">
                            <img src="<?php echo $data_product['preview'];?>">
                        </a>
<?php      else: ?>
                            <img src="/image/noimage.png">
<?php      endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="title">
                          <a href="<?php echo Yii::app()->createUrl('catalog/product', array('id'=>$id_product));?>"><?php echo $data_product['title']?></a>
                        </div>
                    </td>
                    <td>
                        <a class="minus" product_hash="<?php echo $hash;?>"></a>
                        <div class="count">
                          <input type="text" class="value" id="product-<?php echo $hash;?>" maxlength="4" product_hash="<?php echo $hash;?>" value="<?php echo $product['count']?>">
                        </div>
                        <a class="plus" product_hash="<?php echo $hash;?>"></a>
                    </td>
                    <td>
                        <span class="price"><?php echo $data_product['price']?></span>
                        <span class="sing-rub">⃏</span>
                    </td>
                    <td>
                        <span class="price" id="product-cost-<?php echo $hash;?>"><?php echo $product['cost']?> </span>
                        <span class="sing-rub">⃏</span>
                    </td>
                    <td>
                        <a href="#" class="delete" product_hash="<?php echo $hash;?>"><img src="/image/close.png"></a>
                    </td>
                  </tr>
<?php  endforeach; ?>
              </tbody>
              </table>
                <div class="total">
                    <div class="count">
                        <span class="type"><?php echo Yii::t('order', 'Items in basket')?></span>
                        <span class="value" id="order_total_count"><?php echo $basket['total']['count']?></span>
                    </div>

                    <div class="cost">
                        <span class="type"><?php echo Yii::t('order', 'Total cost')?>:</span>
                        <span class="value" id="order_total_cost"><?php echo $basket['total']['cost'];?></span>
                        <span class="sing-rub">⃏</span>
                    </div>

                    <div class="send">
                        <a href="<?php echo Yii::app()->createUrl('order/send')?>">
                             <button class="button"><?php echo Yii::t('order', 'Checkout')?><button>
                        </a>
                    </div>

                    <div class="clear">
                        <a href="<?php echo Yii::app()->createUrl('order/clear')?>" onClick="return confirm('<?php echo Yii::t('order', 'Are you sure?')?>');">
                             <button class="button"><?php echo Yii::t('order', 'Clear basket')?><button>
                        </a>
                    </div>

                </div>
            </div>
<?php else: ?>
    <?php echo Yii::t('order', 'Basket is empty')?>
<?php endif; ?>