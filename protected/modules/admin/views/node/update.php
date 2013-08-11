<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $nodeId = $model->id_node;

    $this->title = Yii::t('site', 'Update node');
    $this->breadcrumbs = array(
        Yii::t('site', 'Update')
    );

    $this->renderPartial('/node/_form', array(
        'model' => $model
    ));
?>