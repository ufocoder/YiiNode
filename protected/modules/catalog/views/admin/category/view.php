<?php
    /* @var $this categoryController */
    /* @var $model category */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('category/update', array('id'=>$model->id_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('category/delete', array('id'=>$model->id_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update category");
    $this->breadcrumbs=array(
        Yii::t('site', 'Сategory #{id}', array('{id}'=>$model->id_category))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update category'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete category'), 'url' => Yii::app()->createUrl('category/delete', array('id'=>$model->id_category, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
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
        /*
        array(
            'name'  => 'id_parent',
            'value' => !empty($model->id_parent)?($model->Parent->title):null,
        ),
        */
        array(
            'name'  => 'time_created',
            'value' => !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
        ),
        array(
            'name'  => 'time_updated',
            'value' => !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
        ),
        array(
            'name'  => 'image',
            'type'  => 'raw',
            'value' => ($model->image)?(CHtml::image($imagePath.$model->image)):null,
        ),
        'notice',
        'enabled'
    ),
)); ?>