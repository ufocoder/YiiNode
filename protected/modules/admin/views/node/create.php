<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $this->title = Yii::t('site', 'Create node');
    $this->breadcrumbs = array(
        Yii::t('site', 'Create')
    );

    $this->renderPartial('/node/_form', array(
        'model' => $model,
        'nodes' => $nodes,
        'modules' => $modules
    ));
?>