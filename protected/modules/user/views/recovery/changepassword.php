<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route, array(
            'activekey' => $activekey,
            'email' => $email,
        )),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        )
    ));

    $this->title  = Yii::t('site', 'Change password');
    $flagRegister = Yii::app()->getSetting('userAllowRegister', Yii::app()->controller->module->allowRegister);
?>

    <?php echo $form->errorSummary($model); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'password'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'password', array('placeholder' => Yii::t('site', 'Enter your new password'))); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'verifyPassword'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'verifyPassword', array('placeholder' => Yii::t('site', 'Enter your new password again'))); ?>
        </div>
    </div>

    <hr>
    <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
            <?php echo CHtml::link(Yii::t("site", "Forgot password?"), Yii::app()->user->recoveryUrl); ?>
            <?php if ($flagRegister):?> |
                <?php echo CHtml::link(Yii::t("site", "Registration"), Yii::app()->user->registrationUrl); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-actions">
        <?php echo CHtml::button(Yii::t('site', 'Restore'), array('type'=>'submit')); ?>
    </div>

<?php $this->endWidget(); ?>