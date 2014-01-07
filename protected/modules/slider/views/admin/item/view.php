<?php
    $this->title = Yii::t('slider', 'View slide');
    $this->breadcrumbs=array(
        Yii::t('site', 'Template'),
        Yii::t('site', 'Slider')=>array('index'),
        $model->title=>array('view', 'id'=>$model->id_slider),
        Yii::t('site', 'View')
    );

    $image = $model->image;
    $thumb = Yii::app()->image->thumbSrcOf($image, array('resize' => array('width' => 350)));
?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'title',
        array(
            'name'=>'time_created',
            'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
        ),
        array(
            'name'=>'time_updated',
            'value'=> !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
        ),
        array(
            'name'  => 'image',
            'type' => 'raw',
            'value' => !empty($image)?(CHtml::link(CHtml::image($thumb), $image)):null,
        ),
        'content',
        array(
            'name'=>'enabled',
            'value'=> !empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled"),
        ),
    ),
)); ?>