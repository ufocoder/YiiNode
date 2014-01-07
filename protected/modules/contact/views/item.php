<div>
<h3><?php echo CHtml::encode($data->title)?></h3>

<?php if (!empty($data->image)):
    $thumb = Yii::app()->image->thumbSrcOf($data->image, array('resize' => array('width' => 150)));
?>
        <div class="item-image">
            <img src="<?php echo $thumb;?>">
        </div>
<?php endif; ?>

<?php if (!empty($data->content)):?>
	<div><?php echo $data->content;?></div>
<?php endif; ?>

<?php
	$attributes = array('worktime', 'email', 'icq', 'phone', 'skype');
	foreach($attributes as $attribute):
		if (!empty($data->$attribute)):
?>
<div>
	<?php echo $data->getAttributeLabel($attribute); ?> : <?php echo $data->$attribute;?>
</div>
<?php
		endif;
	endforeach;
?>

<?php
    if (!empty($data->map_lat) && !empty($data->map_long))
        $this->widget('ext.maps.widgets.YandexMapPoint', array(
            'containerId' => 'ymap',
            'htmlOptions'=>array(
                'style'=> 'width:100%; height: 300px; margin: 10px 0;'
            ),
            'mapPoint' => array($data->map_lat, $data->map_long)
        ));
?>

</div>
