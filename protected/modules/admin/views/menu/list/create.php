<?php
	/* @var $this ListController */
	/* @var $model MenuList */
	$this->title = Yii::t("site", "Create menu");
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list') => array('/admin/menu/list'),
		Yii::t('site', 'Create'),
	);
?>
<?php echo $this->renderPartial('/menu/list/_form', array('model'=>$model)); ?>