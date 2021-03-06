<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'slider-form',
        'type'=>'horizontal',
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        )
    ));
?>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->textFieldRow($model, 'title', array('class'=>'span6')); ?>

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
    <?php echo $form->labelEx($model, 'x_image', array('class'=>'control-label')); ?>
    <div class="controls">
     <?php if (!$model->isNewRecord && $model->image):
            $image = $model->image;
            $thumb = Yii::app()->image->thumbSrcOf($image, array('resize' => array('width' => 350)));
     ?>
            <p>
                <div><?php echo CHtml::link(CHtml::image($thumb), $image); ?></div>
            </p>
    <?php endif; ?>
            <?php echo $form->fileField($model, 'x_image', array('attachId'=>$fileID, 'class'=>'attach-input', 'style'=>'display: none;')); ?>
            <?php echo CHtml::textField(null, null, array('attachId'=>$fileID, 'class' => 'attach-name span4', 'readonly'=>'true')); ?>
            <?php echo CHtml::link(Yii::t('site', 'Choose'), '#', array('attachId'=>$fileID, 'class'=>'attach-button btn'));?>
    </div>
</div>


<?php echo $form->colorpickerRow($model, 'background'); ?>

<?php echo $form->textFieldRow($model, 'position', array('class'=>'span6')); ?>
<?php echo $form->textAreaRow($model, 'content', array('class'=>'span6', 'rows'=>4)); ?>
<?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?>


<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>