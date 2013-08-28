<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        )
    ));

    $this->title =  Yii::t('site', 'Password recovery');
?>

    <?php echo $form->errorSummary($model); ?>


    <div class="control-group">
        <?php echo $form->labelEx($model, 'login_or_email'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'login_or_email', array('placeholder' => Yii::t('site', 'Enter login or email'))); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'captcha', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php $this->widget('CCaptcha', array(
                    'captchaAction' => Yii::app()->createUrl('/user/recovery/captcha'),
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

    <hr>
    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::link(Yii::t("site", "Login"), Yii::app()->user->loginUrl); ?> |
            <?php echo CHtml::link(Yii::t("site", "Registration"), Yii::app()->user->registrationUrl); ?>
        </div>
    </div>


    <div class="form-actions">
        <?php echo CHtml::button(Yii::t('site', 'Restore'), array('type'=>'submit')); ?>
    </div>

<?php $this->endWidget(); ?>