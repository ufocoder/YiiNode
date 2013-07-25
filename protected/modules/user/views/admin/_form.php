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
    
    Yii::app()->user->setFlash('warning', Yii::t('all', 'Fields with <span class="required">*</span> are required.'));
?>

<?php echo $form->errorSummary($model, $profile); ?>
<?php echo $form->textFieldRow($model, 'username', array('class'=>'span4')); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span4')); ?>
<?php echo $form->textFieldRow($model, 'email', array('class'=>'span4')); ?>
<?php echo $form->dropDownListRow($model,'superuser',User::itemAlias('AdminStatus'));?>
<?php echo $form->dropDownListRow($model,'superuser',User::itemAlias('UserStatus'));?>

<?php
    $profileFields=$profile->getFields();
    if (!empty($profileFields))
        foreach($profileFields as $field):
            if ($widgetEdit = $field->widgetEdit($profile)):
                echo $widgetEdit;
            elseif ($field->range):
                echo $form->dropDownListRow($profile, $field->varname, Profile::range($field->range));
            elseif ($field->field_type == "TEXT"): 
                echo $form->textAreaRow($profile, $field->varname, array('rows'=>6, 'cols'=>50));
            else:
                echo $form->textFieldRow($profile, $field->varname, array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
            endif; 
        endforeach;
?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('all', 'Create') : Yii::t('all', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('all', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>