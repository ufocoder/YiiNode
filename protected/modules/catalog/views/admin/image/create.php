<?php


    $product = !empty($this->data_product->title)?$this->data_product->title:Yii::t('site', 'Product #{id}', array('{id}'=>$this->data_product->id_product));
    $nodeId  = Yii::app()->getNodeId();

    $this->title = $product . ": " . Yii::t('catalog', 'Add image');
    $this->breadcrumbs=array(
        $product=>array('/product/view', 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id'=>$this->data_product->id_product),
        Yii::t('site', 'Image list')=>array('/image/index', 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id_product'=>$this->data_product->id_product),
        Yii::t('site', 'Add')
    );

?>
<?php echo $this->renderPartial('/admin/image/_form', array('model'=>$model)); ?>