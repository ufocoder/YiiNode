<?php

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('image/update', array('id'=>$model->id_image, 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id_product'=>$this->data_product->id_product));
    $deleteUrl = Yii::app()->createUrl('image/delete', array('id'=>$model->id_image, 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id_product'=>$this->data_product->id_product));

    $product = !empty($this->data_product->title)?$this->data_product->title:Yii::t('site', 'Product #{id}', array('{id}'=>$this->data_product->id_product));
    $image   = !empty($model->title)?$model->title:Yii::t('site', 'Image #{id}', array('{id}'=>$model->id_image));

    $this->title = $product . ": " . $image;
    $this->breadcrumbs=array(
        $product => array('/product/view', 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id'=>$this->data_product->id_product),
        Yii::t('site', 'Image list')=>array('/image/index', 'nodeAdmin' => true, 'nodeId' => $nodeId, 'id_product'=>$this->data_product->id_product),
        $image
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update image'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete image'), 'url' => $deleteUrl, 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );

?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'title',
        array(
            'name'=>'time_created',
            'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
        ),
        array(
            'name'=>'time_updated',
            'value'=> !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
        ),
        array(
            'name'  => 'image',
            'type' => 'raw',
            'value' => ($model->image)?(CHtml::image($model->getUploadUrl().$model->image)):null,
        ),
        array(
            'name'=>'enabled',
            'value'=> !empty($data->enabled)?Yii::t("all", "Enabled"):Yii::t("all", "Disabled"),
        ),
    ),
)); ?>