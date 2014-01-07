<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'filter' => $model,
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'name'=>'id_order',
            'header'=>'#',
            'htmlOptions'=>array('style'=>'width: 70px'),
        ),
        'person_name',
        'contact_phone',
        'contact_email',
        array(
            'name'=>'id_order_status',
            'value'=>'OrderStatus::getStatus($data->id_order_status)',
            'filter' =>  OrderStatus::getStatus(),
        ),
        array(
            'name'  => 'time_created',
            'value' => '!empty($data->time_created)?date("d.m.y, H:i", $data->time_created):null',
        ),
        array(
            'header'=> Yii::t('site', 'Price'),
            'value' => '$data->cost_product'
        ),
        array(
            'header'=> Yii::t('catalog', 'Cost on discount'),
            'value' => 'round($data->cost_product / 100 * (100-$data->discount), 2) '
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("/admin/order/default/view", array("id"=>$data->primaryKey))',
            'updateButtonUrl' => 'Yii::app()->createUrl("/admin/order/default/update", array("id"=>$data->primaryKey))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/order/default/delete", array("id"=>$data->primaryKey))'
        ),
    ),
)); ?>