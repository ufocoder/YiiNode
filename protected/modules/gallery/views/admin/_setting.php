<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'setting-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl('default/setting', array('nodeAdmin'=>true, 'nodeId'=> Yii::app()->getNodeId())),
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        ),
    ));

    $resizeOptions = GallerySetting::values('resize');
?>
<?php echo $form->errorSummary($model); ?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Content settings');?></legend>
    <?php echo $form->textFieldRow($model, 'pager', array('class'=>'span6')); ?>
    <?php echo $form->textFieldRow($model, 'column', array('class'=>'span6')); ?>
</fieldset>

<fieldset>
    <legend><?php echo Yii::t('site', 'Resize settings');?></legend>
    <?php if (!empty($resizeOptions)): ?>
        <?php echo $form->dropDownListRow($model, 'resize', $resizeOptions); ?>
    <?php endif;?>
    <?php echo $form->textFieldRow($model, 'width', array('class'=>'span6')); ?>
    <?php echo $form->textFieldRow($model, 'height', array('class'=>'span6')); ?>
</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Save'))); ?>
</div>

<?php $this->endWidget(); ?>