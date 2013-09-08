<?php
    $this->title = Yii::t('catalog', 'Brand list');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('brand/create', array('nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId())), 'icon'=>'white plus')
    );
    $this->breadcrumbs = array(
        Yii::t('catalog', 'Brand list')
    );

?>
<?php echo $this->renderPartial('/admin/brand/_grid', array('model'=>$model)); ?>