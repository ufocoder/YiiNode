<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped condensed',
	'dataProvider' => $model->search($menu->id_menu_list),
	'template' => "{items}{pager}",
	'columns' => array(
		'title',
		'position',
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
			'template'=>'{view} {update} {delete}',
			'htmlOptions'=>array('style'=>'width: 80px'),

            'viewButtonUrl' => 'Yii::app()->createUrl("/admin/menu/item/view", array("id"=>$data->primaryKey, "id_menu_list"=>'.$menu->id_menu_list.'))',
            'updateButtonUrl' => 'Yii::app()->createUrl("/admin/menu/item/update", array("id"=>$data->primaryKey, "id_menu_list"=>'.$menu->id_menu_list.'))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/menu/item/delete", array("id"=>$data->primaryKey, "id_menu_list"=>'.$menu->id_menu_list.'))'
		),
	),
)); ?>
