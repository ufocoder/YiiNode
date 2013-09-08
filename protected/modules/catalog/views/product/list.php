<?php

    Yii::app()->getClientScript()->registerCssFile("/css/plugin/fancybox.css");
    Yii::app()->getClientScript()->registerCssFile("/css/plugin/fancybox.thumbs.css");
    Yii::app()->getClientScript()->registerScriptFile("/js/fancybox/jquery.fancybox.pack.js");
    Yii::app()->getClientScript()->registerScriptFile("/js/fancybox/helpers/thumbs.js");
    Yii::app()->getClientScript()->registerScript("product-list-fancybox", 
                                                  "$('a[rel=\"product-image\"]').fancybox({helpers : {"
                                                 ."  'title': { type: 'over' },"
                                                 ."  'thumbs': { width : 50, height : 50 },"
                                                 ."}});",
                                                  CClientScript::POS_END);

 if (!empty($category->content)):?>
<div class="wraper">
    <?php echo $category->content;?>
</div>
<?php endif; ?>
<?php 
    if (!empty($products)): 
        $path = modules\catalog\models\Product::model()->getThumbPath('image','thumb');
?>

<div class="product-filter">

<?php
    $fields = array(
        'price'=>Yii::t('catalog', 'By price'),
        'title'=>Yii::t('catalog', 'By title'),
        'residue'=>Yii::t('catalog', 'By availability')
    );

    $dirs   = $products[0]->values('filter', 'direction');
    $pagers = $products[0]->values('filter', 'pager');

?>

  <div class="filter-field">
    <span class="title"><?php echo Yii::t('catalog', 'Sort by')?>:</span>
<?php
    foreach($fields as $field => $title):
        if ($filter['field']==$field){
            $index  = (array_search($filter['direction'], $dirs) + 1) % count($dirs);
            $dir    = $dirs[$index];
        }
        else{
            $dir = $filter['direction'];
        }

        $url = Yii::app()->createUrl('catalog/category', array(
            'id'    => $category->id,
            'order' => $field,
            'pager' => $filter['pager'],
            'dir'   => $dir
        ));
?>
    <a href="<?php echo $url?>"<?php echo ($filter['field']==$field?' class="active"':null);?>>
        <span class="dot"><?php echo $title;?></span>
        <span class="arrow<?php echo ($filter['field']==$field&&$filter['direction']=="asc"?' up':' down');?>"></span>
    </a>
<?php  endforeach; ?>
    
  </div>
  
  <div class="filter-pager">
    <span class="title"><?php echo Yii::t('catalog', 'Page by')?>:</span>
<?php
    foreach($pagers as $pager):
        $url = Yii::app()->createUrl('catalog/category', array(
            'id'    => $category->id,
            'order' => $filter['field'],
            'pager' => $pager,
            'dir'   => $filter['direction']
        ));
?>
    <a href="<?php echo $url;?>"<?php echo ($filter['pager']==$pager?' class="active"':null);?>>
        <span class="dot"><?php echo $pager?></span>
    </a>
<?php  endforeach; ?>
  </div>
</div>


    <table class="product-table">
        <tbody>
<?php 
    $i=0;
    $step = 3; 
    $c = count($products);
    $lastRow = (int)($c / ($step + 1)) * $step;
    foreach($products as $product): 
        if ($i % $step == 0): 
?>
            <tr>
<?php      endif; ?>
                <td<?php echo ($i<$step?' width="'.ceil(100 / $step).'%"':null)?>>
        <table class="block-box product-box">
            <tr class="box-top">
                <td class="box-top-left"></td>
                <td class="box-top-content"></td>
                <td class="box-top-right"></td>
            </tr>
            <tr class="box-middle">
                <td class="box-middle-left"></td>
                <td class="box-middle-content">
                <div class="product-item">
                    <div class="item-image">
<?php
        if (!empty($product['preview']))
            $image = $product['preview'];
        elseif (!empty($product['image']))
            $image = $product['image'];
        else
            $image = null;
        
        if ($image): 
            if (!preg_match("/^http\:\/\//i", $image))
                $image = $path.$image;
?>
                        <a href="<?php echo $image?>" rel="product-image" title="<?php echo CHtml::encode($product['title'])?>">
                            <img src="<?php echo $image;?>">
                        </a>
<?php      else: ?>
                            <img src="/image/noimage.png">
<?php      endif; ?>
                    </div>
                    <a href="<?php echo Yii::app()->createUrl('/catalog/product', array('id'=>$product['id']))?>">
                    <div class="product-title">
                        <div class="grey-line"></div>
                            <?php echo $product['title']?>
                    </div>
                    </a>
                    <div class="product-sizes" id_product="<?php echo $product['id']?>">
<?php  
    if (!empty($product->size)): 
        $c = count($product->size);
?>
                        <span class="gray"><?php echo Yii::t('catalog', 'Sizes')?>:</span>
<?php      foreach($product->size as $size): ?>
                        <span class="attribute-size" id_size="<?php echo $size->id?>"><?php echo $size->code;?></span>
<?php      endforeach;?>
                                                        
<?php  endif; ?>
                    </div>

                    <div class="product-price">
                        <span class="price"><?php echo $product['price']?> <span class="sing-rub">⃏</span></span>
                        <a class="add-basket" id_product="<?php echo $product['id']?>" href="#"><span class="add2"></span></a>
                    </div>
       
                </td>
                <td class="box-middle-right"></td>
            </tr>
            <tr class="box-bottom">
                <td class="box-bottom-left"></td>
                <td class="box-bottom-content"></td>
                <td class="box-bottom-right"></td>
            </tr>
        </table>

<?php  if ($lastRow > $i):?>
        <div class="dots"></div>
<?php  endif; ?>
                                </td>
<?php if ($i % $step == ($step -1)): ?>
                                </tr>
<?php endif; ?>
<?php $i++; endforeach; ?>
<?php
    if ($i % $step != ($step -1)): 
    for($i=$i % $step; $i<$step; $i++):
?>
                                <td></td>
<?php  endfor;?>
                            </tr>
<?php endif; ?>
                        </tbody></table>

<?php 
    $this->renderPartial('//pager', array(
        'id' => 'product-pager',
        'pages' => $pages
    )); 
?>
<?php endif; ?>