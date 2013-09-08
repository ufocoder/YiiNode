<?php
    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('product/view', array('id'=>$model->id_product, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('product/delete', array('id'=>$model->id_product, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update product");
    $this->breadcrumbs=array(
        Yii::t('site', 'Product list')=>array('/product/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Product #{id}', array('{id}'=>$model->id_product)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View product'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete product'), 'url' => Yii::app()->createUrl('product/delete', array('id'=>$model->id_product, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/product/_form', array(
    'field'=>$field,
    'model'=>$model,
    'stores'=>$stores
)); ?>