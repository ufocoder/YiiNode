<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider' => $model->search(),
    'template' => "{items}{pager}",
    'columns' => array(
        array(
            'name'  => 'title',
            'header'=> Yii::t('site', 'Title'),
            'value' => 'CHtml::link($data->title, Yii::app()->createURL("/category/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => '.Yii::app()->getNodeId().')))',
            'type' => 'raw',
        ),
        array(
            'value' => 'CHtml::image(Yii::app()->image->thumbSrcOf($data->image, array("resize" => array("width" => 50, "height"=>50))))',
            'name'  => 'image',
            'type'  => 'raw',
        ),
        array(
            'header' => Yii::t('site', 'Count of images'),
            'value' => '$data->Count'
        ),
        array(
            'name' => 'time_created',
            'value' => '$data->date_created'
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
                    'url'   => 'Yii::app()->createUrl("category/moveup", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                ),
                'down' => array(
                    'icon'  => 'arrow-down',
                    'label' => Yii::t('site', 'Move down'),
                    'url'   => 'Yii::app()->createUrl("category/movedown", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                )
            )
        ),
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled")',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style'=>'width: 90px'),
            'template' => '{images} {view} {update} {delete}',
            'viewButtonUrl' => 'Yii::app()->createUrl("category/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("category/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("category/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'buttons' => array(
                'images' => array(
                    'icon'  => 'file',
                    'label' => Yii::t('site', 'Images'),
                    'url'   => 'Yii::app()->createUrl("default/index", array("id_category"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                )
            )
        ),
    ),
));