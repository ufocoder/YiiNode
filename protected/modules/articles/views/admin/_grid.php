<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider' => $model->search(),
    'template' => "{items}{pager}",
    'columns' => array(
        array(
            'name'=>'id_article',
            'header'=>'#',
            'htmlOptions'=>array(
                'style'=>'width: 80px;'
            )
        ),
        'title',
        array(
            'name'  => 'time_published',
            'value' => '!empty($data->time_published)?date("m.d.y, H:i:s", $data->time_published):null',
            'htmlOptions' => array(
                'style' => 'width: 120px;'
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
        ),
    ),
));