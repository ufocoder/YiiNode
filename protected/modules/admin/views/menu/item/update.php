<?php
	/* @var $this BlockController */
	/* @var $model Block */

	$this->title = Yii::t("site", "Update menu item #{title}", array('{title}'=>$model->title));
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list') => array('/admin/menu/list'),
		$menu->title => array('/admin/menu/item/index','id_menu_list'=>$menu->id_menu_list),
		$model->title => array('view','id'=>$model->id_menu_item, 'id_menu_list'=>$menu->id_menu_list),
		Yii::t('site', 'Update'),
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
?>
<?php echo $this->renderPartial('_form', array(
	'model' => $model,
	'menu' => $menu
)); ?>