<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('tag/update', array('id'=>$model->id_article_tag, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('tag/delete', array('id'=>$model->id_article_tag, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update tag");
    $this->breadcrumbs=array(
        Yii::t('site', 'Article #{id}', array('{id}'=>$model->id_article_tag))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update tag'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete tag'), 'url' => $deleteUrl, 'icon'=>'trash',
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
            'weight',
            array(
                'name'  => 'enabled',
                'value' => !empty($model->enabled)?Yii::t('site', 'Yes'):Yii::t('site', 'No')
            )
        )
    )); ?>
</fieldset>