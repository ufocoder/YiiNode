<?php
    /* @var $this DefaultController */
    /* @var $model GalleryImage */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('default/update', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_gallery_image, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    if (empty($model->Category->title))
        $categoryTitle = Yii::t('site', 'Gallery category #{id}', array('{id}'=>$model->id_gallery_category));
    else
        $categoryTitle = $model->Category->title;

    $this->title = Yii::t("site", "Update image");
    $this->breadcrumbs=array(
        $categoryTitle => Yii::app()->createUrl('category/view', array('id'=>$model->id_gallery_category, 'nodeAdmin' => true, 'nodeId' => $nodeId)),
        Yii::t('site', 'Image #{id}', array('{id}'=>$model->id_gallery_image))
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

    $image = $model->image;
    $thumb = Yii::app()->image->thumbSrcOf($image, array('resize' => array('width' => 350)));
?>

<fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'attributes'=>array(
            'title',
            array(
                'name'  => 'image',
                'value' => !empty($image)?(CHtml::link(CHtml::image($thumb), $image)):null,
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