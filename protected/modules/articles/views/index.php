<?php foreach($model as $item):
	$url = Yii::app()->createUrl('/articles/default/view', array('id'=>$item->id_article, 'nodeId'=>$item->id_node));
?>
<h4><a href="<?php echo $url ?>"><?php echo CHtml::encode($item->title)?></a></h4>
<small><i class="icon icon-calendar"></i><?php echo Yii::t('site', 'Pudlish date'); ?>: <?php echo date("d.m.Y", $item->time_published); ?></small><br>
<div><?php echo $item->notice;?></div>
<?php endforeach; ?>