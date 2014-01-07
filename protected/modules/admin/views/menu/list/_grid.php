<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped condensed',
	'dataProvider' => $model->search(),
	'template' => "{items}{pager}",
	'columns' => array(
		'title',
		'slug',
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
			'template'=>'{list} {view} {update} {delete}',
			'htmlOptions'=>array('style'=>'width: 100px'),
			'buttons' => array(
				'list' => array(
					'icon'  => 'list',
					'label' => Yii::t('site', 'Menu items'),
					'url'   => 'Yii::app()->createUrl("/admin/menu/item/index", array("id_menu_list"=>$data->primaryKey))',
				)
			)
		),
	),
)); ?>
