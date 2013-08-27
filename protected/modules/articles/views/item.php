<?php
	$params = array('nodeId'=>$data->id_node);

	if (!empty($data->slug))
		$params['slug'] = $data->slug;
	elseif (!empty($data->id_article))
		$params['id'] = $data->id_article;

	$url = Yii::app()->createUrl('/articles/default/view', $params);
?>
<h4><a href="<?php echo $url ?>"><?php echo CHtml::encode($data->title)?></a></h4>
<small><i class="icon icon-calendar"></i><?php echo Yii::t('site', 'Pudlish date'); ?>: <?php echo date("d.m.Y", $data->time_published); ?></small><br>
<div><?php echo $data->notice;?></div>