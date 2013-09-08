<?php
    if (!empty($data_product)):
        Yii::app()->getClientScript()->registerScriptFile("/js/jquery.bikecarousel.js");
        Yii::app()->getClientScript()->registerScript("product-carousel","  $('.product-carousel').bikeCarousel({
    item: '.product-item',
    effect: 'fade',
    speed: 400,
    visible: 1,
    buttonNext: '.product-latest .next',
    buttonPrev: '.product-latest .prev',
});", CClientScript::POS_END);

        Yii::app()->getClientScript()->registerCssFile("/css/plugin/fancybox.css");
        Yii::app()->getClientScript()->registerScriptFile("/js/fancybox/jquery.fancybox.pack.js");
        Yii::app()->getClientScript()->registerScript("product-latest-fancybox",
                                                  "$('a[rel=\"product-image-latest\"]').fancybox();",
                                                  CClientScript::POS_END);

        $thumb = $data_product[0]->thumbs('image');
        $path = $data_product[0]->getThumbPath('image', 'thumb');
?>
                            <h2><?php echo Yii::t('catalog', 'Latest');?></h2>
                            <table class="block-box blue product-latest">
                                <tr class="box-top">
                                    <td class="box-top-left"></td>
                                    <td class="box-top-content"></td>
                                    <td class="box-top-right"></td>
                                </tr>
                                <tr class="box-middle">
                                    <td class="box-middle-left"></td>
                                    <td class="box-middle-content">
                                        <div class="arrows">
                                            <span class="prev"><span><?php echo Yii::t('site', 'Previous');?></span></span>
                                            <span class="next"><span><?php echo Yii::t('site', 'Next');?></span></span>
                                        </div>

                                        <div class="product-carousel">
                                            <div class="viewport">
                                                <div class="overview">
<?php  foreach($data_product as $item):?>
                                                    <div class="product-item">
                                                        <div class="item-image">
<?php
        if (!empty($item['preview']))
            $image = $item['preview'];
        elseif (!empty($item['image']))
            $image = $item['image'];
        else
            $image = null;

        if ($image):
            if (!preg_match("/^http\:\/\//i", $image))
                $image = $path.$image;
?>
                                                        <a href="<?php echo $image;?>" rel="product-image-latest" title="<?php echo CHtml::encode($item['title'])?>">
                                                            <img src="<?php echo $image;?>">
                                                        </a>
<?php      else: ?>
                                                            <img src="/image/noimage.png">
<?php      endif; ?>
                                                        </div>
                                                        <div class="product-title">
                                                            <div class="white-gradient-bottom"></div>
                                                            <div class="grey-line"></div>
                                                            <a href="<?php echo Yii::app()->createUrl('/catalog/product', array('id'=>$item['id']))?>">
                                                                <?php echo $item['title']?>
                                                            </a>
                                                        </div>
                                                        <div class="product-sizes" id_product="<?php echo $item['id']?>">
<?php
    if (!empty($item->size)):
        $c = count($item->size);
?>
                                                            <span class="title"><?php echo Yii::t('catalog', 'Sizes');?>:</span>
<?php      foreach($item->size as $size): ?>
                                                                <span class="attribute-size" id_size="<?php echo $size->id?>"><?php echo $size->code;?></span>
<?php      endforeach;?>

<?php  endif; ?>
                                                        </div>
                                            <div class="waves-blue"></div>
                                            <div class="product-price">
                                                <span class="price"><?php echo $item['price']?> <span class="sing-rub">⃏</span></span>
                                                <a class="add-basket" id_product="<?php echo $item['id']?>" href="#"><span class="add2"></span></a>
                                            </div>

                                                    </div>
<?php  endforeach; ?>

                                            </div>
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
<?php endif; ?>