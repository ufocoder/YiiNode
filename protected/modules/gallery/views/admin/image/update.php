<?php
    /* @var $this DefaultController */
    /* @var $model GalleryImage */

    $nodeId = Yii::app()->getNodeId();
    $viewUrl = Yii::app()->createUrl('default/view', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update image");
    $this->breadcrumbs=array(
        Yii::t('site', 'Image #{id}', array('{id}'=>$model->id_gallery_image)).": ".CHtml::encode($model->title) => $viewUrl,
        Yii::t('site', 'Update'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View image'), 'url' => $viewUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete image'), 'url' => Yii::app()->createUrl('default/delete', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<?php echo $this->renderPartial('/admin/image/_form', array('model'=>$model)); ?>