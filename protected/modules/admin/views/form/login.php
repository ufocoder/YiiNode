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

    $this->title =  Yii::t('site', 'Control panel');
?>

<?php
    $error_list = $model->getErrors();
    if (!empty($error_list)):
?>
    <div class="alert alert-error">
    <?php foreach($error_list as $errors): ?>
        <ul>
    <?php foreach($errors as $error): ?>
            <li><?php echo $error; ?></li>
    <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
    </div>
<?php
    endif;
?>

<div class="form-signin-wrapper">
    <?php echo $form->textField($model, 'login', array('class' => 'span4 text', 'placeholder' => Yii::t('site', 'Enter login'))); ?>
    <?php echo $form->passwordField($model, 'password', array('class' => 'span4 password', 'placeholder' => Yii::t('site', 'Enter password'))); ?>
    <div>
        <?php $this->widget('CCaptcha', array(
                'captchaAction' => Yii::app()->createUrl('/admin/login/captcha'),
                'showRefreshButton'=>false,
                'clickableImage' =>true,
                'imageOptions' => array(
                    'class' => 'captcha',
                ))
            );
        ?>
        <?php echo CHtml::activeTextField($model, 'verifyCode', array('class'=>'span2 captcha-input', 'placeholder' => Yii::t('site', 'Enter code')))?>
    </div>

    <?php echo $form->labelEx($model, 'rememberMe', array('label'=> $form->checkBox($model, 'rememberMe'). Yii::t('site', 'Remember me next time'), 'class' => 'checkbox')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Login'))); ?>
</div>
    <hr>
    <div style="text-align:center;">
        <?php echo CHtml::link(Yii::t("site", "Forgot password?"), Yii::app()->user->recoveryUrl); ?> |
        <?php echo CHtml::link(Yii::t("site", "Go main page"), array('../')); ?>
    </div>
<?php $this->endWidget(); ?>