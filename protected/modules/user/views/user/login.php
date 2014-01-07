<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions' => array(
            'class' => 'form form-vertical',
            'enctype' => 'multipart/form-data',
            'enableClientValidation'=>true
        ),
    ));

    $this->title = Yii::t('site', 'Authorization');
    $this->breadcrumbs = array(
        Yii::t("site", "Profile")
    );

    $flagRegister = Yii::app()->getSetting('userAllowRegister', Yii::app()->controller->module->allowRegister);
?>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-row">
        <div class="form-label">
            <?php echo $form->labelEx($model, 'login'); ?>
        </div>
        <div class="form-value">
            <?php echo $form->textField($model, 'login', array('placeholder' => Yii::t('site', 'Enter login'))); ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">
            <?php echo $form->labelEx($model, 'password'); ?>
        </div>
        <div class="form-value">
            <?php echo $form->textField($model, 'password', array('placeholder' => Yii::t('site', 'Enter password'))); ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">
            <?php echo $form->labelEx($model, 'captcha'); ?>
        </div>
        <div class="form-value">
            <?php $this->widget('CCaptcha', array(
                    'captchaAction' => Yii::app()->createUrl('/user/login/captcha'),
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

    <div class="form-row">
        <div class="form-label">
            <?php echo $form->labelEx($model, 'rememberMe'); ?>
        </div>
        <div class="form-value">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>
        </div>
    </div>

    <hr>
    <div class="form-row">
        <div class="controls">
            <?php echo CHtml::link(Yii::t("site", "Forgot password?"), Yii::app()->user->recoveryUrl); ?>
            <?php if ($flagRegister):?> |
                <?php echo CHtml::link(Yii::t("site", "Registration"), Yii::app()->user->registrationUrl); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-actions">

        <?php echo CHtml::button(Yii::t('site', 'Login'), array('type'=>'submit')); ?>
    </div>

<?php $this->endWidget(); ?>