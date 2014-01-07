<?
    $this->breadcrumbs = array(
        Yii::t('site', 'Wellcome')
    );
?>
<h3><?php echo Yii::t('site', 'Wellcome');?></h3>
<div class="alert alert-info">
С помощью панели управления вы можете редактировать разделы сайта и изменять его настройки.
</div>
<?php
    if (Yii::app()->hasModule('feedback')):
        $count = 5;
        Yii::import("application.modules.feedback.models.*");
        $model = Feedback::model();
?>
<div>
    <h3><?php echo Yii::t('site', 'Last {limit} feedback request', array('{limit}'=>$count));?></h3>
</div>
<?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped condensed',
        'dataProvider'=>$model->search(null, $count),
        'template'=>"{items}",
        'rowCssClassExpression' => 'empty($data->time_readed)?"info":null',
        'columns' => array(
            'person_name',
            array(
                'header' => Yii::t('site', 'Node'),
                'name' => 'Node.title',
            ),
            array(
                'name' => 'contact_email',
                'htmlOptions' => array(
                    'style' => 'width: 170px'
                )
            ),
            array(
                'name' => 'time_created',
                'value' => '$data->date_created',
                'htmlOptions' => array(
                    'style' => 'width: 150px'
                )
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'htmlOptions'=>array('style'=>'width: 50px'),
                'template'=>'{view} {delete}',
                'viewButtonUrl' => 'Yii::app()->controller->createUrl("/admin/feedback/default/view",array("id"=>$data->primaryKey))',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("/admin/feedback/default/delete",array("id"=>$data->primaryKey))'
            ),
        ),
    ));
?>
<?php endif; ?>


<?php
    if (Yii::app()->hasModule('order')):
        $count = 5;
        Yii::import("application.modules.order.models.*");
        $model = OrderItem::model();
?>
<div>
    <h3><?php echo Yii::t('site', 'Last {limit} orders', array('{limit}'=>$count));?></h3>
</div>
<?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped condensed',
        'dataProvider'=>$model->search($count),
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
    ));
?>
<?php endif; ?>