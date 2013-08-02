<?php
    /* @var $this ArticlesController */
    /* @var $model Article */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('default/update', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('default/delete', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->breadcrumbs=array(
        Yii::t('site', 'Article list') => array('/admin/node/'.$nodeId),
        Yii::t('site', 'Article #{id}', array('{id}'=>$model->id_article))
    );

    $baseUrl = Yii::app()->baseUrl;
    Yii::app()->getClientScript()->registerScriptFile($baseUrl.'/js/admin.js');

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update article'), 'url' => $updateUrl, 'icon'=>'eye-open'),
        array('label'=>Yii::t('site', 'Delete article'), 'url' => Yii::app()->createUrl('default/delete', array('id'=>$model->id_article, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );

    $this->title = Yii::t("site", "Update article");
?>

<fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'title',
			'slug',
			'date_published',
			'image',
			'notice',
			'content',
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