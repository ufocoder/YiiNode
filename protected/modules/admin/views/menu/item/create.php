<?php
	/* @var $this ItemController */
	/* @var $model MenuList */
	$this->title = Yii::t("site", "Create menu item");
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list') => array('/admin/menu/list'),
		$menu->title => array('/admin/menu/item/index','id_menu_list'=>$menu->id_menu_list),
		Yii::t('site', 'Create menu item'),
	);
?>
<?php echo $this->renderPartial('_form', array(
	'model' => $model,
	'menu' => $menu
)); ?>