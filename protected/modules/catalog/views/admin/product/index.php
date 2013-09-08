<?php
    $this->title = Yii::t('catalog', 'Product list');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('product/create', array('nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId())), 'icon'=>'white plus')
    );
    $this->breadcrumbs = array(
        Yii::t('catalog', 'Product list')
    );

?>
<?php echo $this->renderPartial('/admin/product/_grid', array('model'=>$model)); ?>