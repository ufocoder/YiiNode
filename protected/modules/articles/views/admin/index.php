<?php
    /* @var $this ArticlesController */

    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t('site', 'Articles manage');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=> $nodeId)), 'icon'=>'white plus')
    );

    $this->breadcrumbs = array(
        Yii::t('site', 'Article list'),
    );

?>

<?php echo $this->renderPartial('/admin/_grid', array('model'=>$model)); ?>
