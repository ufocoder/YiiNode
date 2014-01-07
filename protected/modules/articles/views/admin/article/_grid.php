<?php

$nodeId = Yii::app()->getNodeId();

$fieldPosition = Yii::app()->getNodeSetting($nodeId, 'fieldPosition', ArticleSetting::values('fieldPosition', 'default'));

$columns = array(
    array(
        'name'=>'id_article',
        'header'=>'#',
        'htmlOptions'=>array(
            'style'=>'width: 80px;'
        )
    ),
    array(
        'name'  => 'title',
        'header'=> Yii::t('site', 'Title'),
        'value' => 'CHtml::link($data->title, Yii::app()->createURL("/default/view", array("id"=>$data->id_article, "nodeAdmin" => true, "nodeId" => '.Yii::app()->getNodeId().')))',
        'type' => 'raw',
    ),
);

if (!empty($fieldPosition))
    $columns[] = 'position';

$columns[] = array(
        'name'  => 'time_published',
        'value' => '$data->date_published',
        'htmlOptions' => array(
            'style' => 'width: 140px;'
        )
    );

$columns[] = array(
        'name'=>'enabled',
        'value'=> '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled")',
    );

$columns[] = array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'htmlOptions' => array('style'=>'width: 70px'),
        'viewButtonUrl' => 'Yii::app()->createUrl("default/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
        'updateButtonUrl' => 'Yii::app()->createUrl("default/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
        'deleteButtonUrl' => 'Yii::app()->createUrl("default/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))'
    );

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider' => $model->search(),
    'template' => "{items}{pager}",
    'columns' => $columns,
));