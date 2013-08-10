<?php
	$url = Yii::app()->createUrl('/articles/default/view', array('id'=>$data->id_article, 'nodeId'=>$data->id_node));
?>
<h4><a href="<?php echo $url ?>"><?php echo CHtml::encode($data->title)?></a></h4>
<small><i class="icon icon-calendar"></i><?php echo Yii::t('site', 'Pudlish date'); ?>: <?php echo date("d.m.Y", $data->time_published); ?></small><br>
<div><?php echo $data->notice;?></div>