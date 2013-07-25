a
<?php foreach($model as $item): ?>

<b><small><?php echo Yii::app()->createNodeUrl($item->id_node, 'articles/default/view', array('id'=>$item->id_article)); ?></small></b>
<a href="<?php echo Yii::app()->createNodeUrl($item->id_node, 'articles/default/view', array('id'=>$item->id_article)); ?>"><h3><?php echo $item->title;?></h3></a>
<div><?php echo $item->content;?></div>
<?php endforeach; ?>