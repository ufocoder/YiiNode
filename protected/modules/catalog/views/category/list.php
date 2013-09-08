<?php
    if (!empty($categories)):
        $path = modules\catalog\models\Category::model()->getThumbPath('image', 'thumb');
?>
<?php if (!empty($title)):?>
                        <h2><?php echo $title?></h2>
<?php endif; ?>
                        <table class="category-table">
                            <tbody>
<?php 
    $i=0;
    $step = 3; 
    foreach($categories as $item): 
        if ($i % $step == 0): 
?>
                            <tr>
<?php      endif; ?>

                                <td<?php echo ($i<$step?' width="'.ceil(100 / $step).'%"':null)?>>
                                    <a href="<?php echo Yii::app()->createUrl('catalog/category', array('id'=>$item['id']))?>"  class="category-item">
                                        <table class="block-box category-box">
                                            <tr class="box-top">
                                                <td class="box-top-left"></td>
                                                <td class="box-top-content"></td>
                                                <td class="box-top-right"></td>
                                            </tr>
                                            <tr class="box-middle">
                                                <td class="box-middle-left"></td>
                                                <td class="box-middle-content">
                                                    <span class="image">
<?php      if ($item['image']): ?>
                                                        <img src="<?php echo $path.$item['image'];?>">
<?php      else: ?>
                                                        <img src="/image/noimage.png">
<?php      endif; ?>
                                                    </span>
                                                    <span class="dots"></span>
                                                    <span class="title"><?php echo $item['title']?></span>
                                                    <span class="position"><?php echo Yii::t('catalog', 'Positions')?>: <span><?php echo $item['count']?></span></span>
                                                </td>
                                                <td class="box-middle-right"></td>
                                            </tr>
                                            <tr class="box-bottom">
                                                <td class="box-bottom-left"></td>
                                                <td class="box-bottom-content"></td>
                                                <td class="box-bottom-right"></td>
                                            </tr>
                                        </table>
                                    </a>
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
<?php endif; ?>