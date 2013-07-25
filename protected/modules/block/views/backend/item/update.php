<?php
    $this->breadcrumbs=array(
        Yii::t('all', 'Simpleblock')=>array('index'),
        $model->title=>array('view', 'id'=>$model->id),
        Yii::t('all', 'Update')
    );

    $this->menu=array(
        array('label'=>Yii::t('all', 'List'), 'url'=>array('/block/default/index')),
        array('label'=>Yii::t('all', 'Update'), 'url'=>array('/block/default/update', 'id'=>$model->id)),
        array('label'=>Yii::t('all', 'View'), 'url'=>array('/block/default/view', 'id'=>$model->id))
    );
?>
<h3><?php echo Yii::t('all', 'Update')?></h3>
<?php echo $this->renderPartial('/item/_form', array('model'=>$model)); ?>