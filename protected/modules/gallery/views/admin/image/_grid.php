<?php

    $columns = array(
        array(
            'value' => 'CHtml::image(Yii::app()->image->thumbSrcOf($data->image, array("resize" => array("width" => 50, "height"=>50))))',
            'name'  => 'image',
            'type'  => 'raw',
        ),
        array(
            'name'  => 'title',
            'header'=> Yii::t('site', 'Title'),
            'value' => 'CHtml::link($data->title, Yii::app()->createURL("/default/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => '.Yii::app()->getNodeId().')))',
            'type' => 'raw',
        ),
        array(
            'header' => Yii::t('site', 'Category'),
            'name' => 'Category.title',
            'value' => '!empty($data->Category)?CHtml::link($data->Category->title, Yii::app()->createUrl("default/index", array("id_category"=>$data->id_gallery_category, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))):null',
            'type' => 'raw'
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
                    'url'   => 'Yii::app()->createUrl("default/moveup", array("id"=>$data->primaryKey, "id_category"=>$data->id_gallery_category, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                ),
                'down' => array(
                    'icon'  => 'arrow-down',
                    'label' => Yii::t('site', 'Move down'),
                    'url'   => 'Yii::app()->createUrl("default/movedown", array("id"=>$data->primaryKey, "id_category"=>$data->id_gallery_category, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                )
            )
        ),
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled")',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style'=>'width: 70px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("default/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("default/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("default/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))'
        )
    );

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped condensed',
        'dataProvider' => $model->search($id_category),
        'template' => "{items}{pager}",
        'columns' => $columns
    ));
?>