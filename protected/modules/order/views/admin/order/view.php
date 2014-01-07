<?php

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('/admin/order/default/update', array('id'=>$model->id_order));
    $deleteUrl = Yii::app()->createUrl('/admin/order/default/delete', array('id'=>$model->id_order));

    $this->title = Yii::t('order', 'View order');
    $this->breadcrumbs=array(
        Yii::t('order', 'Orders')=>Yii::app()->createUrl("/admin/order/default/index"),
        Yii::t('order', 'Order #{id}', array('{id}'=>$model->id_order))
    );

    $this->renderPartial('/admin/_menu');

    $this->actions = array(
        array('label'=>Yii::t('order', 'Update order'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('order', 'Delete order'), 'url' => $deleteUrl, 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('order', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('order', 'Are you sure to delete?'),
            )
        )
    );

    $delivery_type = $model->values('delivery_type', 'title');
?>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'person_name',
        'contact_phone',
        'contact_email',
        array(
            'name' => 'delivery_type',
            'value' =>  $delivery_type[$model->delivery_type],
        ),
        'delivery_addr',
        array(
            'name'=>'time_created',
            'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
        ),
        array(
            'name'  => 'comment',
            'type'  => 'raw',
            'value' => $model->comment
        )
    ),
)); ?>

<div style="margin-bottom: 20px;">
    <h4><?php echo Yii::t('order', 'History');?>:</h4>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => new CArrayDataProvider($rawStatus),
    'type'=>'striped',
    'template' => "{items}{pager}",
    'columns' => array(
        array(
            'header' => Yii::t('order', 'Status'),
            'value'=>'OrderStatus::getStatus($data->id_order_status)',
            'filter' =>  OrderStatus::getStatus(),
        ),
        array(
            'header' => Yii::t('order', 'Comment'),
            'name' => 'comment',
        ),
        array(
            'header'  => Yii::t('order', 'Time created'),
            'value' => '!empty($data->time_created)?date("d.m.y, H:i", $data->time_created):null',
        ),
        array(
            'header'  => Yii::t('order', 'Send notice'),
            'value'=> '!empty($data->send_notice)?"'.Yii::t('site', 'Yes').'":"'.Yii::t('site', 'No').'"',
        ),
    ),
));
?>
</div>

<div style="margin-bottom: 20px;">
    <h4><?php echo Yii::t('order', 'List');?>:</h4>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => new CArrayDataProvider($rawProduct),
    'type'=>'striped',
    'template' => "{items}{pager}",
    'columns' => array(
        /*
        array(
            'name'=>'id',
            'header'=>'#',
            'htmlOptions'=>array('style'=>'min-width: 40px')
        ),
        */
        array(
            'name' => 'article',
            'header' => Yii::t('order', 'Article')
        ),
        array(
            'name' => 'title',
            'header' => Yii::t('order', 'Title')
        ),
        array(
            'name' => 'size',
            'header' => Yii::t('order', 'Size')
        ),
        array(
            'name'=>'price',
            'header' => Yii::t('order', 'Price'),
            'htmlOptions'=>array('style'=>'min-width: 100px')
        ),

        array(
            'name'=>'count',
            'header' => Yii::t('order', 'Count'),
        ),
        array(
            'header'=> Yii::t('order', 'Total'),
            'value' => '$data["count"] * $data["price"]'
        ),
    ),
));
?>
<p align="right">
    <u><?php echo Yii::t('order', 'Total')?>:</u> <?php echo $model->cost_product;?>, <?php echo Yii::t('catalog', 'rub.')?>

<?php
    if ($model->discount):
        $total_discount = round((100-$model->discount) * $model->cost_product/100, 2);
?>
    <br />
    <u><?php echo Yii::t('catalog', 'Cost on discount')?>:</u> <?php echo $total_discount;?>, <?php echo Yii::t('catalog', 'rub.')?>
<?php          endif; ?>
</p>
</div>