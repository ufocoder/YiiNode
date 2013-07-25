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
    
    $this->title =  Yii::t('site', 'Password recovery');
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

    <?php echo $form->textField($model, 'login_or_email', array('class' => 'span4 text', 'placeholder' => Yii::t('site', 'Enter login or email'))); ?>

    <div>
    <?php $this->widget('CCaptcha', array(
            'captchaAction' => '/admin/recovery/captcha', 
            'showRefreshButton'=>false,
            'clickableImage' =>true, 
            'imageOptions' => array(
                'class' => 'captcha',
            ))
        ); 
    ?>
    <?php echo CHtml::activeTextField($model, 'verifyCode', array('class'=>'span2 captcha-input', 'placeholder' => Yii::t('site', 'Enter code')))?>
    </div>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Restore'))); ?>

    <hr>
    <div style="text-align:justify;">
    <?php echo CHtml::link(Yii::t("site", "Authorization"), Yii::app()->user->loginUrl); ?> |
    <?php echo CHtml::link(Yii::t("site", "Go main page"), array('/')); ?>
    </div>

<?php $this->endWidget(); ?>