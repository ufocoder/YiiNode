<?php
    $nodeId = Yii::app()->getNodeId();
    if ($model->isNewRecord)
        $action = Yii::app()->createUrl('/category/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));
    else
        $action = Yii::app()->createUrl('/category/update', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id'=>$model->id_category));

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
        ),
    ));

    $attr = 'category_id';
    $name = CHtml::resolveName($model, $attr);
    $updateId =CHtml::getIdByName($name);
    $containerId = 'openTree';
    $backgroundId = 'backgroudTree';

?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'title', array('class'=>'span6')); ?>
<?php
 // get Title html ID
    $attribute = 'title';
    $htmlOptions = array();
    CHtml::resolveNameID($model, $attribute, $htmlOptions);
    $titleFieldID = $htmlOptions['id'];

    // get Slug html ID
    $htmlOptions = array();
    $attribute = 'slug';
    CHtml::resolveNameID($model, $attribute, $htmlOptions);
    $slugFieldID = $htmlOptions['id'];

    // register script
    $script = "\$(document).ready(function(){\$('#" . $titleFieldID . "').syncTranslit({destination:'" . $slugFieldID . "'});});";
    Yii::app()->getClientScript()->registerScript('article-form-slug', $script);

?>
<?php echo $form->textFieldRow($model, 'slug', array('class'=>'span6')); ?>


<?php if ($model->isNewRecord): ?>
<?php if (!empty($categories)): ?>
<div class="control-group">
    <?php echo $form->labelEx($model, 'category_related', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbSelect2',array(
            'model' => $model,
            'attribute' => 'category_related',
            'asDropDownList' => true,
            'options' => array(
                'placeholder' => Yii::t("site", "Select a category"),
                'width' => '328px',
            ),
            'val' => null,
            'data' => $categories,
        ));
        ?>
    </div>
</div>
<?php echo $form->radioButtonListRow($model, 'category_position', $model->values('position')); ?>
<?php endif; ?>
<?php endif; ?>

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

<div class="control-group">
    <?php echo $form->labelEx($model, 'notice', array('class'=>'control-label')); ?>
    <div class="controls">
    <?php $this->widget('bootstrap.widgets.TbCKEditor', array(
        'model' => $model,
        'attribute' => 'notice',
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

<?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Обнулить')); ?>
</div>

<?php $this->endWidget(); ?>