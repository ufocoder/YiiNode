<?php
    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('category/view', array('id'=>$model->id_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('category/delete', array('id'=>$model->id_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update category");
    $this->breadcrumbs=array(
        Yii::t('site', 'Category #{id}', array('{id}'=>$model->id_category)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View category'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete category'), 'url' => Yii::app()->createUrl('category/delete', array('id'=>$model->id_category, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/category/_form', array(
    'model'=>$model
)); ?>