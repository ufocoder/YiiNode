<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'user-grid',
    'type'=>'striped condensed',
    'template'=>'{items}',
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name' => 'id',
            'type'=>'raw',
            'value' => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
        ),
        array(
            'name' => 'login',
            'type'=>'raw',
            'value' => 'CHtml::link(UHtml::markSearch($data,"login"),array("admin/view","id"=>$data->id))',
        ),
        array(
            'name'=>'email',
            'type'=>'raw',
            'value'=>'$data->email',
        ),
        'create_at',
        'lastvisit_at',
        array(
            'name'=>'superuser',
            'value'=>'User::itemAlias("AdminStatus",$data->superuser)',
            'filter'=>User::itemAlias("AdminStatus"),
        ),
        array(
            'name'=>'status',
            'value'=>'User::itemAlias("UserStatus",$data->status)',
            'filter' => User::itemAlias("UserStatus"),
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>