<?php
    $this->title = Yii::t('catalog', 'Field list');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('field/create', array('nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId())), 'icon'=>'white plus')
    );

    $this->breadcrumbs = array(
        Yii::t('catalog', 'Field list')
    );
?>
<?php echo $this->renderPartial('/admin/field/_grid', array('model'=>$model)); ?>