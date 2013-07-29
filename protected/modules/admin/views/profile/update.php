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

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'user-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route, (!empty($model->isNewRecord)?array():array('id'=>$model->id_user))),
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
    <?php echo $form->textFieldRow($model, 'login', array('class'=>'span6')); ?>
    <?php echo $form->textFieldRow($model, 'email', array('class'=>'span6')); ?>
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
    <label class="control-label required" for="User_login">
        <?php echo $form->labelEx($profile, $field->varname, array('class'=>'control-label')); ?>
        <div class="controls">
            <?php
                if ($widgetEdit = $field->widgetEdit($profile)) {
                    echo $widgetEdit;
                } elseif ($field->range) {
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
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->