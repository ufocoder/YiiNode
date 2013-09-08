<?php
    $this->layout = '//layouts/page';

    Yii::app()->getClientScript()->registerScriptFile("/js/fancybox/jquery.fancybox.pack.js");
    Yii::app()->getClientScript()->registerCssFile("/css/plugin/fancybox.css");
    Yii::app()->getClientScript()->registerScript("product-view-fancybox",
                                                  "$('a[rel=\"product-image\"]').fancybox();",
                                                   CClientScript::POS_END);

    if (!empty($category->title)){
        $this->pageTitle = $category->title;
        $this->breadcrumbs = array(
            'Каталог'=>array('/catalog')
        );

        foreach($parents as $parent)
            $this->breadcrumbs[$parent->title] = array('/catalog/category', 'id'=>$parent->id);
        $this->breadcrumbs[$category->title] = array('/catalog/category', 'id'=>$category->id);
    }
    else{
        $this->pageTitle = Yii::t('site', 'Catalog');
        $this->breadcrumbs = array(
            Yii::t('site', 'Catalog')
        );
    }

    $this->pageTitle = $product->title;
    $imageProductPath = $product->getThumbPath('image', 'medium');
?>
                <div class="product-detail">
                    <div class="detail-notice">
<?php  if (!empty($product->notice)):?>
                        <div class="content">
                            <?php echo $product->notice;?>
                        </div>
<?php  endif; ?>
                        <div class="detail-box">

                        <div class="waves-red"></div>
<?php if (!empty($product->size)):?>
                            <div class="product-sizes"  id_product="<?php echo $product['id']?>">
                                <div class="title"><?php echo Yii::t('catalog', 'Size')?>. <a href="#"><?php echo Yii::t('catalog', 'Table sizes')?></a></div>
                                <ul class="attribute-sizes">
<?php      foreach($product->size as $size): ?>
                                    <li class="attribute-size" id_size="<?php echo $size->id?>"><?php echo $size->code?></li>
<?php      endforeach; ?>
                                </ul>
                            </div>
<?php endif; ?>

                            <div class="product-price">
                                <span class="price" id="product-price"><?php echo $product['price']?> <span class="sing-rub">⃏</span></span>
                                <a class="add-basket" id_product="<?php echo $product['id']?>" href="#"><span class="add2"></span></a>
                            </div>
                        </div>
                    </div>

                    <div class="detail-images">
<?php
    $image = $product['image'];

    if ($image):
        if (!preg_match("/^http\:\/\//i", $image))
            $image = $imageProductPath.$image;

?>
                        <div class="image-main">
                            <a href="<?php echo $image?>" rel="product-image" title="<?php echo CHtml::encode($product['title'])?>">
                                <img src="<?php echo $image?>" alt="<?php echo $product['title']?>">
                            </a>
                        </div>
<?php  endif; ?>

<?php  if (!empty($product->images)): ?>
                        <div class="image-more">
                            <ul>
<?php
        foreach($product->images as $item):
            if (!preg_match("/^http\:\/\//i", $item))
                $image = $imageProductPath.$image;
?>
                                <li><a href="#"><img src="<?php echo $imagePicturePath.$item['image']?>" alt="" ></a></li>
<?php      endforeach; ?>
                            </ul>
                        </div>
<?php  endif; ?>

                    </div>
                </div>


<?php  if (!empty($product->params)): ?>
                <div class="product-attributes">
                    <h3><?php echo Yii::t('catalog', 'Product characteristics')?></h3>
                    <ul>
<?php      foreach($product->params as $attribute): ?>
                        <li>
                            <div class="title"><?php echo $attribute->attributes?></div>
                            <div class="value">Хлопок, полиэстирол</div>
                        </li>
<?php      endforeach; ?>
                    </ul>
                </div>
<?php  endif; ?>
