<?php
	$image = $data->image;
	$thumb = Yii::app()->image->thumbSrcOf($data->image, array('resize' => array('width' => $width, 'height'=>$height, 'master'=>$resize)));
	$width = round(100 / $column);
?>
	<a href="<?php echo $image;?>" rel="gallery-image">
		<img src="<?php echo $thumb;?>" alt="<?php echo !empty($data->title)?CHtml::encode($data->title):null; ?>"  title="<?php echo !empty($data->title)?CHtml::encode($data->title):null; ?>">
<?php if (!empty($data->title) && $showTitle):?>
		<div><?php echo $data->title;?></div>
<?php endif; ?>
	</a>