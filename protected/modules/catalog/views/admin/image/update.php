<?php

    $nodeId  = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('image/view', array('id'=>$model->id_image, 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id_product'=>$this->data_product->id_product));
    $deleteUrl = Yii::app()->createUrl('image/delete', array('id'=>$model->id_image, 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id_product'=>$this->data_product->id_product));

    $product = !empty($this->data_product->title)?$this->data_product->title:Yii::t('site', 'Product #{id}', array('{id}'=>$this->data_product->id_product));
    $image   = !empty($model->title)?$model->title:Yii::t('site', 'Image #{id}', array('{id}'=>$model->id_image));

    $this->title = $product . ": " . $image;
    $this->breadcrumbs=array(
        $product => array('/product/view', 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id'=>$this->data_product->id_product),
        Yii::t('site', 'Image list')=>array('/image/index', 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id_product'=>$this->data_product->id_product),
        $image=>$viewUrl,
        Yii::t('site', 'Update')
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View image'),   'url' => $viewUrl,   'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete image'), 'url' => $deleteUrl, 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );

?>
<?php echo $this->renderPartial('/admin/image/_form', array('model'=>$model)); ?>