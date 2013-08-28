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
            'name'  => 'module',
            'value' => '!empty($data->module)?Yii::t(\'module\', $data->module):null;'
        ),
        array(
            'name'  => 'enabled',
            'value' => '!empty($data->enabled)?"<i class=\'icon icon-ok\'></i>":"<i class=\'icon icon-remove\'></i>";',
            'type'  => 'raw',
            'htmlOptions' => array(
                'style' => 'width: 100px;',
            )
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