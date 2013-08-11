<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
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
    <?php echo $form->textFieldRow($model, 'login', array('class'=>'span6')); ?>
    <?php echo $form->textFieldRow($model, 'password', array('class'=>'span6')); ?>
    <?php echo $form->textFieldRow($model, 'verifyPassword', array('class'=>'span6')); ?>
    <?php echo $form->textFieldRow($model, 'email', array('class'=>'span6')); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'verifyCode', array('class'=>'control-label')); ?>
        <div class="span4">
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
        <?php
        if ($field->range) {
            echo $form->dropDownListRow($profile,$field->varname,Profile::range($field->range));
        } elseif ($field->field_type=="TEXT") {
            echo$form->textAreaRow($profile,$field->varname,array('rows'=>6, 'cols'=>50));
        } else {
            echo $form->textFieldRow($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
        }
         ?>
<?php   endforeach; ?>
</fieldset>
<?php endif; ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Register'))); ?>
    </div>

<?php $this->endWidget(); ?>