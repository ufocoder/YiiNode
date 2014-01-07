<?php
	/* @var $this BlockController */
	/* @var $model Block */

	$this->title = Yii::t("site", "Update info block #{id}", array('{id}'=>$model->id_block));
	$this->breadcrumbs = array(
		Yii::t('site', 'Template'),
		Yii::t('site', 'Block list') => array('/admin/block'),
		$model->title => array('view','id'=>$model->id_block),
		Yii::t('site', 'Update'),
	);

	$this->titleButton = array(
		array('label'=>Yii::t('site', 'Delete'), 'url'=>array('delete', 'id'=>$model->id_block),
			'type' => 'danger',
			'icon' => 'white trash',
			'htmlOptions'=>array(
				'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
				'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
			)
		)
	);


?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>