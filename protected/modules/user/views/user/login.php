<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        )
    ));

    $this->title = Yii::t('site', 'Authorization');
    $this->breadcrumbs = array(
        Yii::t("site", "Profile")
    );
?>

    <?php echo $form->errorSummary($model); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'login'); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'login', array('placeholder' => Yii::t('site', 'Enter login'))); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'password'); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'password', array('placeholder' => Yii::t('site', 'Enter password'))); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'captcha', array('class'=>'control-label')); ?>
        <div class="controls">
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

    <div class="control-group">
        <?php echo $form->labelEx($model, 'rememberMe'); ?>
        <div class="controls">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>
        </div>
    </div>

    <hr>
    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::link(Yii::t("site", "Forgot password?"), Yii::app()->user->recoveryUrl); ?> |
            <?php echo CHtml::link(Yii::t("site", "Registration"), Yii::app()->user->registrationUrl); ?>
        </div>
    </div>

    <div class="form-actions">
        <?php echo CHtml::button(Yii::t('site', 'Login'), array('type'=>'submit')); ?>
    </div>

<?php $this->endWidget(); ?>