<?php
	/* @var $this ItemController */
	/* @var $model MenuItem */

	$this->title = Yii::t("site", "View menu item #{id}", array('{id}'=>$model->title));
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list') => array('/admin/menu/list'),
		$menu->title => array('/admin/menu/item/index','id_menu_list'=>$menu->id_menu_list),
		$model->title
	);

	$this->titleButton = array(
		array('label'=>Yii::t('site', 'Delete'), 'url'=>array(
				'delete',
				'id' => $model->id_menu_item,
				'id_menu_list' => $menu->id_menu_list
			),
			'type' => 'danger',
			'icon' => 'white trash',
			'htmlOptions'=>array(
				'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
				'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
			)
		)
	);

	$resize_icon = Yii::app()->image->thumbSrcOf($model->icon, array('resize' => array('width' => 350)));
	$resize_image = Yii::app()->image->thumbSrcOf($model->image, array('resize' => array('width' => 350)));

?>

<?php

$attributes = array(
	'title',
	'alttitle',
	'position',
	array(
		'name'  => 'image',
		'value' => !empty($model->icon)?(CHtml::link(CHtml::image($resize_icon), $model->icon)):null,
		'type'  => 'raw'
	),
	array(
		'name'  => 'image',
		'value' => !empty($model->image)?(CHtml::link(CHtml::image($resize_image), $model->image)):null,
		'type'  => 'raw'
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

$this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>$attributes
)); ?>
