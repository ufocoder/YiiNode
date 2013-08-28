<?php
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t('site', 'Update');
    $this->breadcrumbs=array(
        Yii::t("site", "Profile") => array('/'.$this->module->id.'/profile'),
        Yii::t("site", "Update")
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View profile'), 'url'=>array('index'), 'icon'=>'user'),
        array('label'=>Yii::t('site', 'Change password'), 'url'=>array('changepassword'), 'icon'=>'lock'),
    );

    $this->title = Yii::t("site", "Update profile");
?>
<?php

    /* @var $this UserController */
    /* @var $model User */
    /* @var $form BootActiveForm */

    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'user-form',
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

<fieldset>
    <legend><h4><?php echo Yii::t('site', 'Account information'); ?></h4></legend>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'login'); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'login', array('placeholder' => Yii::t('site', 'Enter login'))); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'email'); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'email', array('placeholder' => Yii::t('site', 'Enter login'))); ?>
        </div>
    </div>

</fieldset>

<?php
    $profile = $model->profile;
    $profileFields = $profile->getFields();
    if ($profileFields):
?>
<fieldset>
    <legend><h4><?php echo Yii::t('site', 'Profile information'); ?></h4></legend>
<?php foreach($profileFields as $field): ?>
    <div class="control-group">
        <?php echo $form->labelEx($profile, $field->varname, array('class'=>'control-label')); ?>
        <div class="controls">
            <?php
                if ($field->range) {
                    echo $form->dropDownList($profile, $field->varname, Profile::range($field->range));
                } elseif ($field->field_type=="TEXT") {
                    echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
                } else {
                    echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
                }
                echo $form->error($profile,$field->varname);
            ?>
        </div>
    </div>
<?php endforeach; ?>
</fieldset>
<?php endif; ?>

    <div class="form-actions">
        <?php echo CHtml::button($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save'), array('type'=>'submit')); ?>
        <?php echo CHtml::button(Yii::t('site', 'Clear'), array('type'=>'reset')); ?>
    </div>

<?php $this->endWidget(); ?>