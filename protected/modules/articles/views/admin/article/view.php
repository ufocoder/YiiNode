<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('default/update', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update article");
    $this->breadcrumbs=array(
        Yii::t('site', 'Article #{id}', array('{id}'=>$model->id_article))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update article'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete article'), 'url' => $deleteUrl, 'icon'=>'trash',
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
            'slug',
            'date_published',
            array(
                'name'  => 'image',
                'value' => !empty($image)?(CHtml::link(CHtml::image($thumb), $image)):null,
                'type'  => 'raw'
            ),
            array(
                'name'  => 'enabled',
                'value' => !empty($model->enabled)?Yii::t('site', 'Yes'):Yii::t('site', 'No')
            )
        )
    )); ?>
</fieldset>

<fieldset>
    <legend><?php echo Yii::t('site', 'Meta information')?></legend>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'attributes'=>array(
            'meta_keywords',
            'meta_description',
        )
    )); ?>
</fieldset>

<fieldset>
    <legend><?php echo Yii::t('site', 'Content')?></legend>
    <div><?php echo $model->notice;?></div>
    <div><?php echo $model->content;?></div>
</fieldset>