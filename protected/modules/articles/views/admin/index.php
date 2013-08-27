<?php
    /* @var $this ArticlesController */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t('site', 'Manage articles');
    $this->actions = array(
        array('label'=>Yii::t('site', 'Add article'), 'url'=> Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'plus'),
        array('label'=>Yii::t('site', 'Article list'), 'url'=> Yii::app()->createUrl('default/index', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'list'),
        array('label'=>Yii::t('site', 'Settings'), 'url'=> Yii::app()->createUrl('default/setting', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'cog'),
    );

    $this->breadcrumbs = array(
        Yii::t('site', 'Item list'),
    );

?>

<?php echo $this->renderPartial('/admin/_grid', array('model'=>$model)); ?>
