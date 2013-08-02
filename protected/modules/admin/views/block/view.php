<?php
	/* @var $this BlockController */
	/* @var $model Block */

	$this->breadcrumbs = array(
		Yii::t('site', 'Block list') => array('/admin/block'),
		CHtml::encode($model->title)
	);

	$baseUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerScriptFile($baseUrl.'/js/admin.js');

	$this->titleButton = array(
		array('label'=>Yii::t('site', 'Delete info block'), 'url'=>array('delete', 'id'=>$model->id_block),
			'type' => 'danger',
			'icon' => 'white trash',
			'htmlOptions'=>array(
				'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
				'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
			)
		)
	);

	$this->title = Yii::t("site", "View info block #{id}", array('{id}'=>$model->id_block));
?>

<?php

$attributes = array(
	array(
		'label' => Yii::t('site', 'Title'),
		'value' => $model->title,
	),
	array(
		'label' => Yii::t('site', 'Type'),
		'value' => $model->type,
	),
	array(
		'name'=>'time_created',
		'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
	),
	array(
		'name'=>'time_updated',
		'value'=> !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
	),
);

switch($model->type){
	default:
		$attributes[] = 'content';
	break;

	case $model::TYPE_HTML:
		$attributes[] = array(
			'name' => 'content',
			'type' => 'raw'
		);
	break;

	case $model::TYPE_FILE:
		$attributes[] = array(
		);
	break;
}

$this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>$attributes
)); ?>
