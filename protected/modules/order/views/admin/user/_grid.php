<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->user(),
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'name'=>'id_user',
            'header'=>'#'
        ),
        'person_name',
        'contact_phone',
        'contact_email',
        array(
            'header' => Yii::t('catalog', 'Discount').' %',
            'name'=>'discount',
        ),
        array(
            'header'=> Yii::t('order', 'Summa').', '.Yii::t('catalog', 'rub.'),
            'value' => '$data->cost_product'
        ),

        array(
            'header'=> Yii::t('catalog', 'With discount').', '.Yii::t('catalog', 'rub.'),
            'value' => '$data->cost_discount'
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'template'=>'{update}',
            'updateButtonUrl' => 'Yii::app()->createUrl("/admin/order/user/update", array("id"=>$data->primaryKey))',
        ),
    ),
)); ?>