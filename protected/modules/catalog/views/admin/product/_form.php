<?php

    $nodeId = Yii::app()->getNodeId();
    if ($model->isNewRecord)
        $action = Yii::app()->createUrl('/product/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));
    else
        $action = Yii::app()->createUrl('/product/update', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id'=>$model->id_product));

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'service-form',
        'type'=>'horizontal',
        'action'=>$action,
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        )
    ));

    $brand_list     = CatalogBrand::model()->findAll();
    $category_list  = Yii::app()->db->createCommand()
        ->select("id_category, CONCAT(REPEAT('—', level-1), IF(level > 0,' ',''), `title`) as `title`")
        ->from(CatalogCategory::model()->tableName())
        ->queryAll();

    $data_brand     = CHtml::listData($brand_list, 'id_brand', 'title');
    $data_category  = CHtml::listData($category_list, 'id_category', 'title');

?>

<?php echo $form->errorSummary($model); ?>

<fieldset>
    <legend><?php echo Yii::t('site','Content'); ?></legend>

    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>
    <?php echo $form->textFieldRow($model, 'article', array('class'=>'span8')); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'id_brand', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php if (!empty($data_brand)): ?>
                <?php echo $form->dropDownList($model, 'id_brand', $data_brand, array('class'=>'span8', 'empty'=> Yii::t('site', 'Without brand'))); ?>
            <?php else: ?>
                <?php echo Yii::t('site', 'Without brand'); ?>
            <?php endif;?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'id_category', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php if (!empty($data_category)): ?>
            <?php $this->widget('bootstrap.widgets.TbSelect2', array(
                'model' => $model,
                'attribute' => 'id_category',
                'options' => array(
                    'width' => '328px',
                    'placeholder' => Yii::t("site", "Without category"),
                ),
                'data' => $data_category,
                'htmlOptions'=>array(
                    'multiple'=>'multiple',
                ),
            ));?>
            <?php else: ?>
            <?php echo Yii::t('site', 'Without category'); ?>
        <?php endif; ?>
        </div>
    </div>

    <?php
        $fileID = substr(md5(time()), 0, 8);
        $script = "$(document).ready(function(){
        $('.attach-button').live('click', function(){
            var id = $(this).attr('attachId');
            $('.attach-input[attachId='+id+']').trigger('click');
            return false;
        });

        $('.attach-input').live('change', function(e){
            var id = $(this).attr('attachId'),
                val = $(this).val(),
                file = val.split(/[\\\]/);
            console.log(file);
            $('.attach-name[attachId='+id+']').val(file[file.length-1]);
        });
    });";
        Yii::app()->clientScript->registerScript('form-file', $script);
    ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'image', array('class'=>'control-label')); ?>
        <div class="controls">
         <?php if (!$model->isNewRecord && $model->image): ?>
                <p>
                    <div><?php echo CHtml::link(CHtml::image($model->getUploadUrl().$model->image), $model->getUploadUrl().$model->image); ?></div>
                    <div><?php echo $form->checkBox($model,'delete_image', array('style' => 'float: left; margin-right: 5px;;')); ?> <?php echo $form->labelEx($model,'delete_image'); ?></div>
                </p>
        <?php endif; ?>
                <?php echo $form->fileField($model, 'x_image', array('attachId'=>$fileID, 'class'=>'attach-input', 'style'=>'display: none;')); ?>
                <?php echo CHtml::textField(null, null, array('attachId'=>$fileID, 'class' => 'attach-name span4', 'readonly'=>'true')); ?>
                <?php echo CHtml::link(Yii::t('site', 'Choose'), '#', array('attachId'=>$fileID, 'class'=>'attach-button btn'));?>
        </div>
    </div>

    <?php echo $form->textAreaRow($model, 'notice', array('class'=>'span8', 'rows'=>6)); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'content', array('class'=>'control-label')); ?>
        <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbCKEditor', array(
            'model' => $model,
            'attribute' => 'content',
            'editorOptions' => array(
                'language' => Yii::app()->language,
                'toolbar' => array(
                    array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
                    array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
                    array('Link','Unlink','Anchor'),
                )
            )
        )); ?>
        </div>
    </div>
    <?php echo $form->textFieldRow($model, 'price', array('class'=>'span6')); ?>
    <?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?>
</fieldset>

<?php
    $fields = $model->getFields();
    if (!empty($fields)):
?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Field list'); ?></legend>
    <?php foreach($fields as $varname => $params): ?>
        <?php echo $form->textFieldRow($field, $varname, array('class'=>'span6')); ?>
    <?php endforeach; ?>
</fieldset>
<?php endif; ?>

<fieldset>
    <legend><?php echo Yii::t('catalog', 'Amount'); ?></legend>

    <?php
        foreach($stores as $store):
            $id   = 'ProductStore_'.$store->id_store;
            $name = 'ProductStore['.$store->id_store.']';
            $value = isset($model->stores[$store->id_store])?$model->stores[$store->id_store]['value']:null;
    ?>
    <div class="control-group">
        <?php echo CHtml::label($store->title, $id, array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo CHtml::textField($name, $value, array('class'=>'span6', 'id'=>$id)); ?>
        </div>
    </div>
    <?php endforeach; ?>
    <hr>

    <?php echo $form->textFieldRow($model, 'store', array('class'=>'span6')); ?>

    <?php echo $form->textFieldRow($model, 'count', array('class'=>'span6')); ?>

</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>