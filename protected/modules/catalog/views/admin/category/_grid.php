<?php

//$imagePath = $model->getThumbPath('image', 'small');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'category-grid',
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name'=>'id',
            'header'=>'#'
        ),
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?Yii::t("all", "Enabled"):Yii::t("all", "Disabled")',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{move}{update}{delete}',
            'htmlOptions'=>array('style'=>'width: 100px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("category/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("category/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("category/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'buttons'=>array
            (
                'move' => array
                (
                    'label'=>'Переместить',
                    'icon'=>'move',
                    'url'=> 'Yii::app()->createUrl("category/move", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                ),
            ),
        ),
    ),
)); ?>