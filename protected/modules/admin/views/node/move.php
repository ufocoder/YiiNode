<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $nodeId = $model->id_node;

    $this->title = Yii::t('site', 'Update node');
    $this->breadcrumbs = array(
        Yii::t('site', 'Move')
    );

    $this->renderPartial('/node/_move', array(
        'model' => $model,
        'nodes' => $nodes,
    ));
?>