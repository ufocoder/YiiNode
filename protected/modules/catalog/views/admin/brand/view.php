<?php
    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('brand/update', array('id'=>$model->id_brand, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('brand/delete', array('id'=>$model->id_brand, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "View brand");
    $this->breadcrumbs=array(
        Yii::t('site', 'Brand list')=>array('/brand/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()),
        Yii::t('site', 'Brand #{id}', array('{id}'=>$model->id_brand)).": ".CHtml::encode($model->title)
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update brand'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete brand'), 'url' => Yii::app()->createUrl('brand/delete', array('id'=>$model->id_brand, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
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
            'name'  => 'notice',
            'type'  => 'raw',
            'value' => $model->notice
        ),
        'position',
        array(
            'name'=>'slider',
            'value'=> !empty($model->slider)?Yii::t("all", "Enabled"):Yii::t("all", "Disabled"),
        ),
        array(
            'name'=>'enabled',
            'value'=> !empty($model->enabled)?Yii::t("all", "Enabled"):Yii::t("all", "Disabled"),
        ),
    ),
)); ?>