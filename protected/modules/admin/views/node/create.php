<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $this->title = Yii::t('site', 'Create node');
    $this->breadcrumbs = array(
        Yii::t('site', 'Create')
    );

    Yii::app()->user->setFlash('warning', Yii::t('site', 'Fields with <span class="required">*</span> are required.'));

    $this->renderPartial('/node/_form', array(
        'model' => $model,
        'nodes' => $nodes,
        'modules' => $modules
    ));
?>