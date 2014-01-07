<?php
	/* @var $this ListController */
	/* @var $model MenuList */

	$this->title = Yii::t("site", "View menu list #{title}", array('{title}'=>$model->id_menu_list));
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list') => array('/admin/menu/list'),
		CHtml::encode($model->title)
	);

	$this->titleButton = array(
		array('label'=>Yii::t('site', 'Delete'), 'url'=>array('delete', 'id'=>$model->id_menu_list),
			'type' => 'danger',
			'icon' => 'white trash',
			'htmlOptions'=>array(
				'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
				'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
			)
		)
	);

?>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=> array(
		array(
			'label' => Yii::t('site', 'Title'),
			'value' => $model->title,
		),
		'notice',
		array(
			'name'=>'time_created',
			'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
		),
		array(
			'name'=>'time_updated',
			'value'=> !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
		),
	)
)); ?>
