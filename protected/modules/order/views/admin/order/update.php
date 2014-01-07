<?php

    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('/admin/order/default/view', array('id'=>$model->id_order));
    $deleteUrl = Yii::app()->createUrl('/admin/order/default/delete', array('id'=>$model->id_order));

    $this->title = Yii::t('order', 'Update order');
    $this->breadcrumbs=array(
        Yii::t('order', 'Orders')=>Yii::app()->createUrl("/admin/order/default/index"),
        Yii::t('order', 'Order #{id}', array('{id}'=>$model->id_order)) => $viewUrl,
        Yii::t('order', 'Update')
    );

    $this->renderPartial('/admin/_menu');

    $this->actions = array(
        array('label'=>Yii::t('order', 'View order'), 'url' => $viewUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('order', 'Delete order'), 'url' => $deleteUrl, 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('order', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('order', 'Are you sure to delete?'),
            )
        )
    );


?>
<?php echo $this->renderPartial('/admin/order/_form', array('model'=>$model)); ?>