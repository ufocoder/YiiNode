<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'slider-grid',
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createURL("/admin/slider/admin/view", array("id"=>$data->primaryKey, "nodeAdmin" => true)))',
            'type' => 'raw',
        ),
        array(
            'value' => 'CHtml::image(Yii::app()->image->thumbSrcOf($data->image, array("resize" => array("width" => 50, "height"=>50))))',
            'name'  => 'image',
            'type'  => 'raw',
        ),
        array(
            'name'=>'time_created',
            'value'=> '!empty($data->time_created)?date("m.d.y, H:i", $data->time_created):null',
        ),
        array(
            'name'=>'time_updated',
            'value'=> '!empty($data->time_updated)?date("m.d.y, H:i", $data->time_updated):null',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style'=>'width: 60px'),
            'header' => Yii::t('site', 'Position'),
            'template' => '<center>{up} {down}</center>',
            'buttons' => array(
                'up' => array(
                    'icon'  => 'arrow-up',
                    'label' => Yii::t('site', 'Move up'),
                    'url'   => 'Yii::app()->createUrl("admin/slider/admin/moveup", array("id"=>$data->primaryKey))',
                ),
                'down' => array(
                    'icon'  => 'arrow-down',
                    'label' => Yii::t('site', 'Move down'),
                    'url'   => 'Yii::app()->createUrl("admin/slider/admin/movedown", array("id"=>$data->primaryKey))',
                )
            )
        ),
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled")',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>