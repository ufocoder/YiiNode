<?php

    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('brand/view', array('id'=>$model->id_brand, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('brand/delete', array('id'=>$model->id_brand, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update brand");
    $this->breadcrumbs=array(
        Yii::t('site', 'Brand list')=>array('/brand/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Brand #{id}', array('{id}'=>$model->id_brand)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View brand'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete brand'), 'url' => Yii::app()->createUrl('brand/delete', array('id'=>$model->id_brand, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );

?>
<?php echo $this->renderPartial('/admin/brand/_form', array('model'=>$model)); ?>