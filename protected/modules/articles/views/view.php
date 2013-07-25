<h1><?php echo $model->title;?></h1>
<b><small><?php echo Yii::app()->createUrl('articles/default/view', array('id'=>$model->id_article)); ?></small></b>
<div><?php echo $model->content;?></div>