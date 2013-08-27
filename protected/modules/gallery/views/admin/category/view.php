<?php
    /* @var $this DefaultController */
    /* @var $model GalleryCategory */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('category/update', array('id'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('category/delete', array('id'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update category");
    $this->breadcrumbs=array(
        Yii::t('site', 'Gallery category #{id}', array('{id}'=>$model->id_gallery_category))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update category'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete category'), 'url' => Yii::app()->createUrl('category/delete', array('id'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
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
                'header' => Yii::t('site', 'Count of images in category'),
                'value' => GalleryImage::model()->category()->count('', array('id_gallery_category'=>$model->id_gallery_category)),
                'type'  => 'raw'
            ),
            array(
                'name'  => 'image',
                'value' => !empty($model->image)?CHtml::image($model->getUploadUrl().$model->image):null,
                'type'  => 'raw'
            ),
            array(
                'name'  => 'enabled',
                'value' => !empty($model->enabled)?Yii::t('site', 'Yes'):Yii::t('site', 'No')
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