<?php
    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t("site", "Create product");
    $this->breadcrumbs=array(
        Yii::t('site', 'Product list')=>array('/product/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Create product')
    );
?>
<?php echo $this->renderPartial('/admin/product/_form', array(
    'field'=>$field,
    'model'=>$model,
    'stores'=>$stores
)); ?>