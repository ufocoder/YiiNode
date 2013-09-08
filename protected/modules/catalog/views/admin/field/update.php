<?php

    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('field/view', array('id'=>$model->id_field, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('field/delete', array('id'=>$model->id_field, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update field");
    $this->breadcrumbs=array(
        Yii::t('site', 'Field list') => array('/field/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Field #{id}', array('{id}'=>$model->id_field)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View field'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete field'), 'url' => Yii::app()->createUrl('field/delete', array('id'=>$model->id_field, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );

?>
<?php echo $this->renderPartial('/admin/field/_form', array('model'=>$model)); ?>