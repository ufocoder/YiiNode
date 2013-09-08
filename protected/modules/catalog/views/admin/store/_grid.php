<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'name'=>'id_store',
            'header'=>'#'
        ),
        'title',
        'alttitle',
        array(
            'name'  => 'time_created',
            'value' => '!empty($data->time_created)?date("m.d.y, H:i", $data->time_created):null',
        ),
        array(
            'name'  => 'time_updated',
            'value' => '!empty($data->time_updated)?date("m.d.y, H:i", $data->time_updated):null',
        ),
        'count',
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style'=>'width: 70px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("store/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("store/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("store/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
        ),
    ),
)); ?>