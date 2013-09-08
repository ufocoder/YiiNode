<?php
    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('store/update', array('id'=>$model->id_store, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('store/delete', array('id'=>$model->id_store, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update store");
    $this->breadcrumbs=array(
        Yii::t('site', 'Store #{id}', array('{id}'=>$model->id_store))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update store'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete store'), 'url' => Yii::app()->createUrl('store/delete', array('id'=>$model->id_store, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
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
        'alttitle',
        array(
            'name'=>'time_created',
            'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
        ),
        array(
            'name'=>'time_updated',
            'value'=> !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
        ),
        array(
            'name'  => 'notice',
            'type'  => 'raw',
            'value' => $model->notice
        ),
        array(
            'name'=>'enabled',
            'value'=> !empty($model->enabled)?Yii::t("site", "Yes"):Yii::t("all", "Disabled"),
        ),
    ),
));
?>