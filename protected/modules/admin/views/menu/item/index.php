<?php
	/* @var $this ItemController */

	$this->title = Yii::t('site', 'Menu items');
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list') => array('/admin/menu/list'),
		$menu->title => array('/admin/menu/list/view','id'=>$menu->id_menu_list),
		Yii::t('site', 'Menu items'),
	);

	$this->titleButton[] = array(
		'label'=>Yii::t('site', 'Create'),
		'url'=>array('create',  'id_menu_list' => $menu->id_menu_list),
		'icon'=>'white plus'
	);
?>

<?php echo $this->renderPartial('/menu/item/_grid', array(
    'model' => $model,
    'menu' => $menu
)); ?>
