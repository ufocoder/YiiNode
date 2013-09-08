<?php
    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t("site", "Create store");
    $this->breadcrumbs=array(
        Yii::t('site', 'Store list')=>array('/store/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Create store')
    );
?>
<?php echo $this->renderPartial('/admin/store/_form', array('model'=>$model)); ?>