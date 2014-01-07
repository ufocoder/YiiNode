<?php
	/* @var $this ListController */
	/* @var $model MenuList */

	$this->title = Yii::t("site", "Update menu #{title}", array('{title}'=>$model->title));
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list') => array('/admin/menu/list'),
		$model->title => array('view','id'=>$model->id_menu_list),
		Yii::t('site', 'Update'),
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
<?php echo $this->renderPartial('/menu/list/_form', array('model'=>$model)); ?>