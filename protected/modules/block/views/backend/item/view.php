<?php
    $this->breadcrumbs=array(
        Yii::t('all', 'Simpleblock')=>array('index'),
        $model->title,
    );

    $this->menu=array(
        array('label'=>Yii::t('all', 'List'), 'url'=>array('/block/default/index')),
        array('label'=>Yii::t('all', 'Update'), 'url'=>array('/block/default/update', 'id'=>$model->id)),
        array('label'=>Yii::t('all', 'View'), 'url'=>array('/block/default/view', 'id'=>$model->id))
    );
?>
<h3><?php echo Yii::t('all', 'View')?> <?php echo $model->id;?></h3>
<?php 
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'attributes'=>array(
            'title',
            array(
                'name'=>'time_created',
                'type'=>'raw',
                'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
            ),
            array(
                'name'=>'time_updated',
                'type'=>'raw',
                'value'=> !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
            ),
            array(
                'name'=>'content',
                'type'=>'raw',
                'value'=>!empty($model->content)?$model->content:Yii::t('all','Empty'),
            ),
        ),
    )); 
?>