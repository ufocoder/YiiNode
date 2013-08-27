<?php
    $this->pageTitle = $item->title;
    $this->breadcrumbs = array(
        Yii::t('site', 'Contact')=>array('/contact'),
        $item->title
    );
?>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$item,
    'attributes'=>array(
        'title',
        'content',
        array(
            'name'=>'time_created',
            'value'=> !empty($item->time_created)?date("m.d.y, h:i", $item->time_created):null,
        ),
        array(
            'name'=>'time_updated',
            'value'=> !empty($item->time_updated)?date("m.d.y, h:i", $item->time_updated):null,
        ),
        array(
            'name'  => 'image',
            'type' => 'raw',
            'value' => ($item->image)?(CHtml::image($imagePath.$item->image)):null,
        ),
        'timework',
        'addr',
        'email',
        'phone',
        'icq',
        'skype',
    ),
)); ?>

<?php
    if (!empty($model->map_lat) && !empty($model->map_long))
        $this->widget('ext.maps.widgets.YandexMapPoint', array(
            'htmlOptions'=>array(
                'style'=> 'width:100%; height: 300px; margin: 10px 0;'
            ),
            'mapPoint' => array($model->map_lat, $model->map_long)
        ));
?>