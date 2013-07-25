<?
    $nodeId = Yii::app()->getNodeID();
?>

<?php foreach($recipes as $recipe): ?>
<div>
    <a href="<?php echo Yii::app()->createUrl('recipe/default/view', array('id'=>$item->id_recipe)); ?>"><h3><?php echo $item->title;?></h3></a>
    <div><?php echo $item->content;?></div>
</div>
<?php endforeach; ?>