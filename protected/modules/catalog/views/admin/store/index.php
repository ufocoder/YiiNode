<?php
    $this->title = Yii::t('catalog', 'Store list');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('store/create', array('nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId())), 'icon'=>'white plus')
    );

    $this->breadcrumbs = array(
        Yii::t('catalog', 'Store list')
    );
?>
<?php echo $this->renderPartial('/admin/store/_grid', array('model'=>$model)); ?>