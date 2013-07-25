<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $this->title = Yii::t('catalog', 'Create node');
    $this->breadcrumbs = array(
        Yii::t('all', 'Structure')=>array('/admin/node'),
        Yii::t('all', 'Create')
    );

    $this->renderPartial('/node/_form', array(
        'model' => $model,
        'nodes' => $nodes,
        'modules' => $modules
    )); 
?>