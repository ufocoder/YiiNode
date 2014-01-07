<?php

$nodeId = Yii::app()->getNodeId();

$columns = array(
    array(
        'name'=>'id_article_tag',
        'header'=>'#',
        'htmlOptions'=>array(
            'style'=>'width: 80px;'
        )
    ),
    array(
        'name'  => 'title',
        'header'=> Yii::t('site', 'Title'),
        'value' => 'CHtml::link($data->title, Yii::app()->createURL("/tag/view", array("id"=>$data->id_article_tag, "nodeAdmin" => true, "nodeId" => '.Yii::app()->getNodeId().')))',
        'type' => 'raw',
    ),
    'count',
    array(
        'name'=>'enabled',
        'value'=> '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled")',
    ),
    array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'htmlOptions' => array('style'=>'width: 70px'),
        'viewButtonUrl' => 'Yii::app()->createUrl("tag/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
        'updateButtonUrl' => 'Yii::app()->createUrl("tag/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
        'deleteButtonUrl' => 'Yii::app()->createUrl("tag/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))'
    )
);

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider' => $model->search(),
    'template' => "{items}{pager}",
    'columns' => $columns,
));