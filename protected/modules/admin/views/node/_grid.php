<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'node-grid',
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name' => 'id_node',
            'header' => '#',
            'htmlOptions' => array(
                'style' => 'width: 40px'
            )
        ),
        array(
            'name' => 'title'
        ),
        array(
            'name' => 'module',
            'value' => '!empty($data->module)?Yii::t("site", "Module ".$data->module):null;'
        ),
        array(
            'name' => 'enabled',
            'value' => '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled");',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{move}{update}{delete}',
            'htmlOptions'=>array('style'=>'width: 100px'),
            'buttons'=>array
            (
                'move' => array
                (
                    'label'=>'Переместить',
                    'icon'=>'move',
                    'url'=> 'Yii::app()->createUrl("admin/node/move", array("id"=>$data->id_node))',
                ),
            ),
        ),
    ),
)); ?>