<?php
	$this->menu = array(
		array('label'=>Yii::t('site', 'Company list')),
		array('label'=>Yii::t('site', 'Add'), 'url'=>array('/admin/company/create')),
		array('label'=>Yii::t('site', 'List'), 'url'=>array('/admin/company/index')),
	);
?>