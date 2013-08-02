<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped condensed',
	'dataProvider' => $model->search(),
	'template' => "{items}{pager}",
	'columns' => array(
		array(
			'header' => Yii::t('site', 'Title'),
			'name' => 'title',
		),
		array(
			'name'  => 'time_created',
			'value' => '!empty($data->time_created)?date("m.d.y, H:i:s", $data->time_created):null',
			'htmlOptions' => array(
				'style' => 'width: 140px'
			)
		),
		array(
			'name'  => 'time_updated',
			'value' => '!empty($data->time_created)?date("m.d.y, H:i:s", $data->time_created):null',
			'htmlOptions' => array(
				'style' => 'width: 140px'
			)
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view} {update}',
			'htmlOptions'=>array('style'=>'width: 30px'),
		),
	),
)); ?>
