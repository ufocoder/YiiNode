<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider' => $model->search(),
    'template' => "{items}{pager}",
    'columns' => array(
        'title',
        array(
            'header' => Yii::t('site', 'Count of images in category'),
            'value' => '$data->count'
        ),
        array(
            'name' => 'time_created',
            'value' => '$data->date_created'
        ),
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled")',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style'=>'width: 90px'),
            'template' => '{images} {view} {update} {delete}',
            'viewButtonUrl' => 'Yii::app()->createUrl("category/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("category/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("category/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'buttons' => array(
                'images' => array(
                    'icon'  => 'file',
                    'label' => Yii::t('site', 'Images'),
                    'url'   => 'Yii::app()->createUrl("default/index", array("id_category"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                )
            )
        ),
    ),
));