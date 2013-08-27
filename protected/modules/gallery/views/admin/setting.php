<?php
    /* @var $this ArticlesController */
    /* @var $model ArticleSetting */

    $nodeId = Yii::app()->getNodeId();
    $this->title = Yii::t("site", "Settings");
    $this->breadcrumbs=array(
        Yii::t('site', 'Settings'),
    );
    $this->actions = array(
        array('label'=>Yii::t('site', 'Add image'), 'url'=> Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'plus'),
        array('label'=>Yii::t('site', 'Add category'), 'url'=> Yii::app()->createUrl('category/create', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'plus'),
        array('label'=>Yii::t('site', 'Images'), 'url'=> Yii::app()->createUrl('default/index', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'list'),
        array('label'=>Yii::t('site', 'Categories'), 'url'=> Yii::app()->createUrl('category/index', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'list'),
        array('label'=>Yii::t('site', 'Settings'), 'url'=> Yii::app()->createUrl('default/setting', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon' => 'cog'),
    );
?>
<?php echo $this->renderPartial('/admin/_setting', array('model'=>$model)); ?>