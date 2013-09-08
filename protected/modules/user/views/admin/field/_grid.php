<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'field-grid',
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'header' => '#',
            'name'   => 'id_user_field',
        ),
        array(
            'name'   => 'varname',
            'type'   => 'raw',
            'value'  => '$data->varname',
        ),
        array(
            'name'   => 'title',
            'value'  => 'Yii::t(\'site\', $data->title)',
        ),
        array(
            'name'   => 'field_type',
            'value'  => '$data->field_type',
            'filter' => ProfileField::values ("field_type"),
        ),
        'field_size',
        //'field_size_min',
        array(
            'name'   => 'required',
            'value'  => 'ProfileField::values ("required",$data->required)',
            'filter' => ProfileField::values ("required"),
        ),
        //'match',
        //'range',
        //'error_message',
        //'other_validator',
        //'default',
        'position',
        array(
            'name'=>'visible',
            'value'=>'ProfileField::values ("visible",$data->visible)',
            'filter'=>ProfileField::values ("visible"),
        ),
        //*/
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>