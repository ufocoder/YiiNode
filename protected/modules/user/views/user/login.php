<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
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

    <?php echo $form->textFieldRow($model, 'login', array('class' => 'span4', 'placeholder' => Yii::t('site', 'Enter login'))); ?>
    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span4 password', 'placeholder' => Yii::t('site', 'Enter password'))); ?>
    <div class="control-group">
        <?php echo $form->label($model, 'captcha', array('class'=>'control-label')); ?>
        <div class="span4">
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

    <?php echo $form->checkBoxRow($model, 'rememberMe'); ?>

    <hr>
    <div class="control-group">
        <label class="control-label"></label>
        <div class="span4">
            <?php echo CHtml::link(Yii::t("site", "Forgot password?"), Yii::app()->user->recoveryUrl); ?> |
            <?php echo CHtml::link(Yii::t("site", "Registration"), Yii::app()->user->registrationUrl); ?>
        </div>
    </div>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Login'))); ?>
    </div>

<?php $this->endWidget(); ?>