<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'action' => Yii::app()->createUrl($this->route, array(
            'activekey' => $activekey,
            'email' => $email,
        )),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        )
    ));

    $this->title =  Yii::t('site', 'Change password');
?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span4 text', 'placeholder' => Yii::t('site', 'Enter your new password'))); ?>
    <?php echo $form->passwordFieldRow($model, 'verifyPassword', array('class' => 'span4 text', 'placeholder' => Yii::t('site', 'Enter your new password again'))); ?>

    <hr>
    <div class="control-group">
        <label class="control-label"></label>
        <div class="span4">
            <?php echo CHtml::link(Yii::t("site", "Forgot password?"), Yii::app()->user->recoveryUrl); ?> |
            <?php echo CHtml::link(Yii::t("site", "Registration"), Yii::app()->user->registrationUrl); ?>
        </div>
    </div>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Restore'))); ?>
    </div>

<?php $this->endWidget(); ?>