<?php
	/* @var $this BlockController */

	$this->breadcrumbs = array(
		Yii::t('site', 'Template'),
		Yii::t('site', 'Info block list'),
	);

	$this->title = Yii::t('site', 'Info block manage');
?>

<?php echo $this->renderPartial('/block/_grid', array('model'=>$model)); ?>
