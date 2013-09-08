<?php
    $this->title = Yii::t('catalog', 'Create brand');
    $this->breadcrumbs = array(
        Yii::t('catalog', 'Brand list')=>array('/brand/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Add')
    );
?>
<?php echo $this->renderPartial('/admin/brand/_form', array('model'=>$model)); ?>