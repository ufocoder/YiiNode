<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'name'=>'id_image',
            'header'=>'#'
        ),
        array(
            'name'  => 'image',
            'type'  => 'raw',
            'value' => '!empty($data->image)?CHtml::image(Yii::app()->image->thumbSrcOf($data->getUploadPath().$data->image,array("resize"=>array("width"=>100,"height"=>100)))):null',
        ),
        array(
            'name'  => 'title',
            'header'=> Yii::t('site', 'Title')
        ),
        array(
            'name'  => 'time_created',
            'value' => '!empty($data->time_created)?date("m.d.y, H:i", $data->time_created):null',
        ),
        array(
            'name'  => 'time_updated',
            'value' => '!empty($data->time_updated)?date("m.d.y, H:i", $data->time_updated):null',
        ),
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?Yii::t("site", "Enabled"):Yii::t("site", "Disabled")',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("/image/view", array("id_product"=>"'.$this->data_product->id_product.'", "id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("/image/update", array("id_product"=>"'.$this->data_product->id_product.'", "id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/image/delete", array("id_product"=>"'.$this->data_product->id_product.'", "id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
        ),
    ),
)); ?>