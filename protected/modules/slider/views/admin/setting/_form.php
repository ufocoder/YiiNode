<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'setting-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'post',
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        ),
    ));

?>
<?php echo $form->errorSummary($model); ?>
<?php /*
<div class="control-group">
    <?php echo $form->labelEx($model,'sliderEnabled', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->checkBox($model, 'sliderEnabled', array()); ?>
    </div>
</div>

<?php echo $form->dropDownListRow($model, 'sliderEffect', $model->values('sliderEffect', 'data'), array('class'=>'span4')); ?>
*/ ?>
<?php echo $form->textFieldRow($model, 'sliderPauseTime', array('class'=>'span4', 'hint'=> Yii::t('site', 'Milliseconds'))); ?>
<?php echo $form->textFieldRow($model, 'sliderAnimSpeed', array('class'=>'span4', 'hint'=> Yii::t('site', 'Milliseconds'))); ?>
<?php echo $form->checkBoxRow($model, 'sliderPauseOnHover'); ?>

<?php echo $form->textFieldRow($model, 'sliderHeight', array('class'=>'span4')); ?>
<?php echo $form->textFieldRow($model, 'sliderWidth', array('class'=>'span4')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>('Сохранить'))); ?>
</div>

<?php $this->endWidget(); ?>