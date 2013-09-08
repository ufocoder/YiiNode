<?php
    $this->title = Yii::t('catalog', 'Create field');
    $this->breadcrumbs = array(
        Yii::t('site', 'Field list')=>array('/field/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Add')
    );
?>
<?php echo $this->renderPartial('/admin/field/_form', array('model'=>$model)); ?>