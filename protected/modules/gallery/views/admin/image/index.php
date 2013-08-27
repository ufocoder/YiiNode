<?php
    /* @var $this DefaultController */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t('site', 'Manage image');

    $this->actions = array(
        array('label'=>Yii::t('site', 'Add image'), 'url'=> Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'plus'),
        array('label'=>Yii::t('site', 'Add category'), 'url'=> Yii::app()->createUrl('category/create', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'plus'),
        array('label'=>Yii::t('site', 'Images'), 'url'=> Yii::app()->createUrl('default/index', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'list'),
        array('label'=>Yii::t('site', 'Categories'), 'url'=> Yii::app()->createUrl('category/index', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'list'),
        array('label'=>Yii::t('site', 'Settings'), 'url'=> Yii::app()->createUrl('default/setting', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'cog'),
    );

    $this->breadcrumbs = array(
        Yii::t('site', 'Item list'),
    );

?>

<?php echo $this->renderPartial('/admin/image/_grid', array('model'=>$model)); ?>
