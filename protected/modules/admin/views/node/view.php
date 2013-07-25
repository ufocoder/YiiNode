<?php
    $this->title = Yii::t('catalog', 'Category list');
    $this->breadcrumbs=array(
        Yii::t('all', 'Catalog')=>array('/catalog'),
        Yii::t('catalog', 'Category list')=>array('index'),
        $model->title=>array('update', 'id'=>$model->id),
        Yii::t('all', 'View')
    );

    $this->renderPartial('/_menu');
?>

<h3><?php echo Yii::t('all', 'View')?> #<?php echo $model->id?></h3>

<h4><?php echo Yii::t('all', 'Page content')?></h4>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'title',
        /*
        array(
            'name'  => 'id_parent',
            'value' => !empty($model->id_parent)?($model->Parent->title):null,
        ),
        */
        array(
            'name'  => 'time_created',
            'value' => !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
        ),
        array(
            'name'  => 'time_updated',
            'value' => !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
        ),
        array(
            'name'  => 'image',
            'type'  => 'raw',
            'value' => ($model->image)?(CHtml::image($imagePath.$model->image)):null,
        ),
        'notice',
        'enabled'
    ),
)); ?>