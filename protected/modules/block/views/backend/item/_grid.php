<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'name'=>'id',
            'header'=>'#',
            'filter'=>false,
        ),
        array(
            'name'=>'title',
            'header'=>Yii::t('all', 'Title'),
        ),
        array(
            'name'=>'time_created',
            'value'=>'date("m.d.y, h:i", $data->time_created)',
        ),
        array(
            'name'=>'time_updated',
            'value'=>'!empty($data->time_updated)?date("m.d.y, h:i", $data->time_updated):null',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view}{update}{delete}',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>