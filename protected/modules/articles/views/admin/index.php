<?php
    /* @var $this ArticlesController */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t('site', 'Article list');
    $this->actions = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'plus'),
    	array('label'=>Yii::t('site', 'List'), 'url'=> Yii::app()->createUrl('default/index', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'list'),
    );

    $this->breadcrumbs = array(
        Yii::t('site', 'Article list'),
    );

?>

<?php echo $this->renderPartial('/admin/_grid', array('model'=>$model)); ?>
