<?php
    /* @var $this ProductController */
    /* @var $model Product */

    $nodeId = Yii::app()->getNodeId();
    $updateUrl = Yii::app()->createUrl('product/update', array('id'=>$model->id_product, 'nodeAdmin' => true, 'nodeId' => $nodeId));
    $deleteUrl = Yii::app()->createUrl('product/delete', array('id'=>$model->id_product, 'nodeAdmin' => true, 'nodeId' => $nodeId));

    $this->title = Yii::t("site", "Update product");
    $this->breadcrumbs=array(
        Yii::t('site', 'Product #{id}', array('{id}'=>$model->id_product))
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'Update product'), 'url' => $updateUrl, 'icon'=>'pencil'),
        array('label'=>Yii::t('site', 'Delete product'), 'url' => Yii::app()->createUrl('product/delete', array('id'=>$model->id_product, 'nodeAdmin' => true, 'nodeId' => $nodeId)), 'icon'=>'trash',
            'htmlOptions'=>array(
                'data-confirm-title' => Yii::t('site', 'Confirm dialog'),
                'data-confirm-content' => Yii::t('site', 'Are you sure to delete?'),
            )
        )
    );
?>
<fieldset>
    <legend><?php echo Yii::t('site','Content'); ?></legend>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'attributes'=>array(
            'title',
            'article',
            array(
                'name' =>'id_brand',
                'value'=> !empty($model->brand->title)?$model->brand->title:null,
            ),
            array(
                'name'   => 'Category.title',
                'header' => Yii::t('site', 'Category'),
                'type'   => 'raw'
            ),
            array(
                'name'=>'time_created',
                'value'=> !empty($model->time_created)?date("m.d.y, h:i", $model->time_created):null,
            ),
            array(
                'name'=>'time_updated',
                'value'=> !empty($model->time_updated)?date("m.d.y, h:i", $model->time_updated):null,
            ),
            array(
                'name'  => 'image',
                'type' => 'raw',
                'value' => ($model->image)?(CHtml::image($model->getUploadUrl().$model->image)):null,
            ),
            'store',
            'price',
            array(
                'name'=>'enabled',
                'value'=> !empty($model->enabled)?Yii::t("all", "Enabled"):Yii::t("all", "Disabled"),
            )
        ),
    )); ?>
</fieldset>

<?php if (!empty($model->notice) || !empty($model->content)): ?>
<fieldset style="margin-bottom: 20px;">
    <legend><?php echo Yii::t('site','Description'); ?></legend>
    <div><?php echo CHtml::encode($model->notice); ?></div>
    <div><?php echo $model->content; ?></div>
</fieldset>
<?php endif; ?>

<?php

$fields = $model->getFields();
if (!empty($fields)):
    $attributes = array();
    foreach ($fields as $field)
        $attributes[] = array(
            'label' => $field->title,
            'value' => $model->field->{$field->varname}
        );
?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Field list'); ?></legend>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model->field,
        'attributes' => $attributes
    )); ?>
</fieldset>
<?php endif; ?>


<?php
    $attributes = array();
    foreach ($model->stores as $store)
        $attributes[] = array(
            'label' => $stores[$store->id_store]->title,
            'value' => $store->value
        );

    $attributes = array_merge($attributes, array('store', 'count'));
?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Amount'); ?></legend>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model->field,
        'attributes' => $attributes
    )); ?>
</fieldset>

