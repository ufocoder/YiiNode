<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'template'=>"{items}{pager}",
    'rowCssClassExpression' => 'empty($data->time_readed)?"info":null',
    'columns' => array(
        'person_name',
        array(
            'header' => Yii::t('site', 'Node'),
            'name' => 'Node.title',
        ),
        array(
            'name' => 'contact_email',
            'htmlOptions' => array(
                'style' => 'width: 170px'
            )
        ),
        array(
            'name' => 'time_created',
            'value' => '$data->date_created',
            'htmlOptions' => array(
                'style' => 'width: 150px'
            )
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'template'=>'{view} {delete}'
        ),
    ),
)); ?>