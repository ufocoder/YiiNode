<?php
    /* @var $this NodeController */
    /* @var $model Node */

    $this->title = Yii::t('catalog', 'Update node');
    $this->breadcrumbs = array(
        Yii::t('all', 'Structure')=>array('/admin/node'),
        $model->title => array('view', 'id'=>$model->id_node),
        Yii::t('all', 'Update')
    );

    $this->renderPartial('/node/_form', array(
        'model' => $model
    )); 
?>