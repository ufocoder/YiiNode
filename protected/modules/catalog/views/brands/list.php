<?php if (!empty($brands)):?>
                        <h2><?php echo Yii::t('catalog', 'Brands')?></h2>
                        
                        <div class="brand-table">
                        <table>
                            <tbody>
<?php 
    $i=0;
    $step = 3; 
    foreach($brands as $brand): 
        if ($i % $step == 0): 
?>
                                <tr>
<?php      endif; ?>
                                    <td>
                                        <a href="#" class="menu-link">
                                            <span class="menu-link-left">
                                                <span class="menu-link-right">
                                                    <span class="title"><?php echo $brand['title'];?></span>
                                                    <span class="count">(<?php echo $brand['count']?>)</span>
                                                </span>
                                            </span>
                                        </a>
                                    </td>
<?php if ($i % $step == ($step -1)): ?>
                                </tr>
<?php endif; ?>
<?php  $i++; endforeach; ?>

<?php
    if ($i % $step != ($step -1)): 
    for($i=$i % $step; $i<$step; $i++):
?>
                                <td></td>
<?php  endfor;?>
                                </tr>
<?php endif; ?>
                        </tbody></table>
                        </div>
<?php endif; ?>