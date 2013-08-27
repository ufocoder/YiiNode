<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('default/update', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update image");
    $this->breadcrumbs=array(
        Yii::t('site', 'Article #{id}', array('{id}'=>$model->id_gallery_image))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update image'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete image'), 'url' => Yii::app()->createUrl('default/delete', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>

<fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'attributes'=>array(
            'title',
            array(
                'name'  => 'image',
                'value' => !empty($model->image)?CHtml::image($model->getUploadUrl().$model->image):null,
                'type'  => 'raw'
            )
        )
    )); ?>
</fieldset>

<?php if (!empty($model->content)): ?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Content')?></legend>
    <div><?php echo $model->content;?></div>
</fieldset>
<?php endif; ?>