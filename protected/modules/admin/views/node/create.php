<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $this->title = Yii::t('catalog', 'Create node');
    $this->breadcrumbs = array(
        Yii::t('all', 'Create')
    );

    Yii::app()->user->setFlash('warning', Yii::t('all', 'Fields with <span class="required">*</span> are required.'));

    $this->renderPartial('/node/_form', array(
        'model' => $model,
        'nodes' => $nodes,
        'modules' => $modules
    ));
?>