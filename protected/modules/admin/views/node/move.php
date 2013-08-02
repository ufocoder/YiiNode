<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $nodeId = $model->id_node;

    $this->title = Yii::t('catalog', 'Update node');
    $this->breadcrumbs = array(
        Yii::t('all', 'Move')
    );

    $this->actions = array(
        array('label' => Yii::t('site', 'Node content'), 'url' => Yii::app()->createUrl('/admin/node/'.$nodeId), 'icon' => 'file'),
        array('label' => Yii::t('site', 'Node settings'), 'url' => Yii::app()->createUrl('/admin/node/update', array('id'=>$nodeId)), 'icon' => 'pencil'),
        array('label' => Yii::t('site', 'Node move'), 'url' => Yii::app()->createUrl('/admin/node/move', array('id'=>$nodeId)), 'icon' => 'move'),
    );

    $this->renderPartial('/node/_move', array(
        'model' => $model,
        'nodes' => $nodes,
    ));
?>