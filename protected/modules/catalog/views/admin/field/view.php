<?php

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('field/update', array('id'=>$model->id_field, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('field/delete', array('id'=>$model->id_field, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update field");
    $this->breadcrumbs=array(
        Yii::t('site', 'Field list') => array('/field/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Field #{id}', array('{id}'=>$model->id_field)).": ".CHtml::encode($model->title)
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update field'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete field'), 'url' => $deleteUrl, 'icon'=>'trash',
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
        'varname',
        'field_size',
        'field_size_min',
        'field_type',
        'default',
        'position',
        'required'
    ),
)); ?>