<?php
	/* @var $this ListController */
	$this->title = Yii::t('site', 'Menu list');
	$this->breadcrumbs = array(
		Yii::t('site', 'Menu list')
	);

	$this->titleButton[] = array(
		'label'=>Yii::t('site', 'Create'),
		'url'=>array('create'),
		'icon'=>'white plus'
	);

?>
<?php echo $this->renderPartial('/menu/list/_grid', array(
	'model'=>$model
)); ?>
