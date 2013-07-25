<?php
	/* @var $this ArticlesController */

	$this->breadcrumbs = array(
		Yii::t('site', 'Article list'),
	);

	$this->titleButton = array(
		array('label'=>Yii::t('site', 'Add'), 'url'=>array('create'))
	);

	$this->title = Yii::t('site', 'Articles manage');
?>

<?php echo $this->renderPartial('/admin/_grid', array('model'=>$model)); ?>
