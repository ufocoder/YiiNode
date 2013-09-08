<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'header'=> '#',
            'name'  => 'id_field'
        ),
        'title',
        'varname',
        'field_type',
        'field_size',
        'field_size_min',
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style'=>'width: 70px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("field/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("field/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("field/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))'
        ),
    ),
)); ?>