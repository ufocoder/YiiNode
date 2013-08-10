<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'user-form',
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

    Yii::app()->user->setFlash('warning', Yii::t('site', 'Fields with <span class="required">*</span> are required.'));
?>


<?php echo $form->errorSummary($model, $profile); ?>
<fieldset>
    <legend><h4><?php echo Yii::t('site', 'Account information'); ?></h4></legend>
    <?php echo $form->textFieldRow($model, 'login', array('class'=>'span4')); ?>
<?php if ($model->isNewRecord):?>
    <?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span4')); ?>
<?php endif; ?>
    <?php echo $form->textFieldRow($model, 'email', array('class'=>'span4')); ?>
    <?php echo $form->dropDownListRow($model, 'role', User::values('role'));?>
    <?php echo $form->dropDownListRow($model, 'status', User::values('status'));?>
</fiedset>

<?php
    $profileFields=$profile->getFields();
    if (!empty($profileFields)):
?>
<fieldset>
    <legend><h4><?php echo Yii::t('site', 'Profile information'); ?></h4></legend>
<?php
        foreach($profileFields as $field):
            if ($field->range):
                echo $form->dropDownListRow($profile, $field->varname, Profile::range($field->range));
            elseif ($field->field_type == "TEXT"):
                echo $form->textAreaRow($profile, $field->varname, array('rows'=>6, 'cols'=>50));
            else:
                echo $form->textFieldRow($profile, $field->varname, array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
            endif;
        endforeach;
?>
</fieldset>
<?php endif; ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>