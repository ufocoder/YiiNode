<?php
    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('store/view', array('id'=>$model->id_store, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('store/delete', array('id'=>$model->id_store, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update store");
    $this->breadcrumbs=array(
        Yii::t('site', 'Store list')=>array('/store/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Store #{id}', array('{id}'=>$model->id_store)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View store'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete store'), 'url' => Yii::app()->createUrl('store/delete', array('id'=>$model->id_store, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/store/_form', array('model'=>$model)); ?>