<?php

    $product = !empty($this->data_product->title)?$this->data_product->title:Yii::t('site', 'Product #{id}', array('{id}'=>$this->data_product->id_product));
    $nodeId  = Yii::app()->getNodeId();

    $this->title = $product . ": " . Yii::t('catalog', 'Image list');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('image/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id_product'=>$this->data_product->id_product)), 'icon'=>'white plus')
    );

    $this->breadcrumbs=array(
        $product => array('/product/view', 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id'=>$this->data_product->id_product),
        Yii::t('site', 'Image list')
    );
?>
<?php echo $this->renderPartial('/admin/image/_grid', array('model'=>$model)); ?>