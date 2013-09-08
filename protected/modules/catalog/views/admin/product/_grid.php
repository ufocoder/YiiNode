<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped condensed',
    'dataProvider'=>$model->search(),
    //'filter' => $model,
    'template'=>"{items}{pager}",
    'columns'=>array(
        array(
            'header'=> '#',
            'name'  => 'id_product'
        ),
        'title',
        array(
            'name'  => 'image',
            'type'  => 'raw',
            'value' => '!empty($data->image)?CHtml::image(Yii::app()->image->thumbSrcOf($data->getUploadPath().$data->image,array("resize"=>array("width"=>75,"height"=>75)))):null',
        ),
        'article',
        array(
            'name'  => 'id_category',
            'type'  => 'html',
            'value' => '!empty($data->category)?(CHtml::link($data->category->title, Yii::app()->createUrl("/category/view", array("id"=>$data->category->id, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId())))):null'
        ),
        array(
            'name'  => 'id_brand',
            'type'  => 'raw',
            'value' => '!empty($data->brand)?CHtml::link($data->brand->title, Yii::app()->createUrl("/brand/view", array("id"=>$data->brand->id_brand, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))):null'
        ),
        'price',
        array(
            'name'=>'enabled',
            'value'=> '!empty($data->enabled)?"<i class=\'icon-ok\'></i>":"<i class=\'icon-remove\'></i>"',
            'type' => 'raw',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view} {update} {picture} {delete}',
            'htmlOptions' => array('style'=>'width: 80px'),
            'viewButtonUrl' => 'Yii::app()->createUrl("product/view", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'updateButtonUrl' => 'Yii::app()->createUrl("product/update", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("product/delete", array("id"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
            'buttons'=>array
            (
                'picture' => array
                (
                    'label'=>Yii::t('site', 'Images'),
                    'icon'=>'picture',
                    'url'=> 'Yii::app()->createUrl("image/index", array("id_product"=>$data->primaryKey, "nodeAdmin" => true, "nodeId" => Yii::app()->getNodeId()))',
                ),
            ),
        ),
    ),
)); ?>