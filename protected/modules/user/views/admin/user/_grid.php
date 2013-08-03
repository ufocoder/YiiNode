<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'user-grid',
    'type' => 'striped condensed',
    'template' => '{items}',
    'filter' => $model,
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name'   => 'id_user',
            'type'   =>'raw',
            'value'  => 'CHtml::link(CHtml::encode($data->id_user), array("/admin/user/admin/update", "id"=>$data->id_user))',
            'header' => '#'
        ),
        array(
            'name'  => 'login',
            'type'  =>'raw',
            'value' => 'CHtml::link(CHtml::encode($data->login), array("/admin/user/admin/view", "id"=>$data->id_user))',
        ),
        array(
            'name'  => 'email',
            'type'  => 'raw',
            'value' => '$data->email',
        ),
        array(
            'name'   => 'role',
            'value'  => '$data->role?User::values("role", $data->role):null',
            'filter' => User::values("role"),
        ),
        'time_created',
        'time_visited',
        array(
            'name'=>'status',
            'value'=>'User::values("status", $data->status)',
            'filter' => User::values("status"),
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>