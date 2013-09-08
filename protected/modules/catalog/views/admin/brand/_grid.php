<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'header'=>'#',
            'name'=>'id_brand',
        ),
        'title',
        'position',
        'count',
        array(
            'name'  => 'time_created',
            'value' => '!empty($data->time_created)?date("m.d.y, H:i", $data->time_created):null',
        ),
        array(
            'name'  => 'time_updated',
            'value' => '!empty($data->time_updated)?date("m.d.y, H:i", $data->time_updated):null',
        ),
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?Yii::t("site", "Yes"):Yii::t("site", "No")',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style'=>'width: 70px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("brand/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("brand/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("brand/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))'
        ),
    ),
)); ?>