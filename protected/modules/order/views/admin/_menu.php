<?php
    $nodeId = Yii::app()->getNodeId();

    $this->actions = array(
        array('label'=>Yii::t('order', 'List'), 'icon'=>'list', 'url'=>Yii::app()->createUrl('/admin/order/default/index')),
        array('label'=>Yii::t('order', 'Clients'), 'icon'=>'user', 'url'=>Yii::app()->createUrl('/admin/order/user/index')),
        array('label'=>Yii::t('order', 'Settings'), 'icon'=>'cog','url'=>Yii::app()->createUrl('/admin/order/setting/index')),
    );
?>