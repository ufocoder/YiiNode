<?php
    $nodeId = Yii::app()->getNodeId();

    $this->title = Yii::t("site", "Create category");
    $this->breadcrumbs=array(
        Yii::t('site', 'Category list')=>array('/category/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Add')
    );
?>
<?php echo $this->renderPartial('/admin/category/_form', array(
    'model'      => $model,
    'categories' => $categories
)); ?>