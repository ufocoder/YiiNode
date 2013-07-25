<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped condensed',
	'dataProvider' => $model->search(),
	'template' => "{items}{pager}",
	'columns' => array(
		array(
			'name'=>'id_article',
			'header'=>'#',
			'htmlOptions'=>array(
				'style'=>'width: 80px;'
			)
		),
		'title',
		array(
			'name'  => 'time_published',
			'value' => '!empty($data->time_published)?date("m.d.y, H:i:s", $data->time_published):null',
			'htmlOptions'=>array(
				'style'=>'width: 120px;'
			)
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'htmlOptions'=>array('style'=>'width: 70px'),
		),
	),
)); ?>
