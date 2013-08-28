<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions' => array(
            'enctype'=>'multipart/form-data'
        ),
    ));

    $this->title = Yii::t('site', 'Registration');
    $this->breadcrumbs = array(
        Yii::t("site", "Registration")
    );
?>

    <?php echo $form->errorSummary(array($model, $profile)); ?>

<fieldset>
    <legend><?php echo Yii::t('site', 'Account information')?></legend>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'login'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'login'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'password'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'password'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'verifyPassword'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'verifyPassword'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'email'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'email'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'verifyCode', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php $this->widget('CCaptcha', array(
                    'captchaAction' => Yii::app()->createUrl('/user/registration/captcha'),
                    'showRefreshButton'=>false,
                    'clickableImage' =>true,
                    'imageOptions' => array(
                        'class' => 'captcha',
                    ))
                );
            ?>
            <br />
            <?php echo CHtml::activeTextField($model, 'verifyCode', array('class'=>'captcha-input', 'placeholder' => Yii::t('site', 'Enter code')))?>
        </div>
    </div>
<fieldset>

<?php
        $profileFields=$profile->getFields();
        if ($profileFields):
?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Profile information')?></legend>
<?php foreach($profileFields as $field): ?>
    <div class="control-group">
        <?php echo $form->labelEx($profile, $field->varname, array('class'=>'control-label')); ?>
        <div class="controls">
            <?php
                if ($field->range)
                    echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
                elseif ($field->field_type=="TEXT")
                    echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
                else
                    echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
             ?>
<?php   endforeach; ?>
</fieldset>
<?php endif; ?>

    <div class="form-actions">
        <?php echo CHtml::button(Yii::t('site', 'Register'), array('type'=>'submit')); ?>
    </div>

<?php $this->endWidget(); ?>