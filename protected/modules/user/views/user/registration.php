<?php 
    $this->pageTitle = UserModule::t("Registration");
    $this->breadcrumbs=array(
        UserModule::t("Profile"),
    );
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'registration-form',
    //'enableAjaxValidation'=>true,
    //'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

    <?php echo $form->errorSummary(array($model,$profile)); ?>
    
    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'login'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->textField($model,'login'); ?>
            <?php echo $form->error($model,'login'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'password'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->passwordField($model,'password'); ?>
            <?php echo $form->error($model,'password'); ?>
            <p class="hint"><?php echo UserModule::t("Minimal password length 4 symbols."); ?></p>
        </div>
    </div>
    
    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'verifyPassword'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->passwordField($model,'verifyPassword'); ?>
            <?php echo $form->error($model,'verifyPassword'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'email'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->textField($model,'email'); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
    </div>
    
<?php 
        $profileFields=$profile->getFields();
        if ($profileFields):
            foreach($profileFields as $field):
?>
    <div class="row">
        <div class="form-label">
        <?php echo $form->labelEx($profile,$field->varname); ?>
        </div>
        <div class="form-element">
        <?php 
        if ($widgetEdit = $field->widgetEdit($profile)) {
            echo $widgetEdit;
        } elseif ($field->range) {
            echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
        } elseif ($field->field_type=="TEXT") {
            echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
        } else {
            echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
        }
         ?>
        <?php echo $form->error($profile,$field->varname); ?>
        </div>
    </div>
<?php
            endforeach;
        endif;
?>

    <?php if (UserModule::doCaptcha('registration')): ?>
    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'verifyCode'); ?>
        </div>
        <div class="form-element">
<?php 
            $this->widget('CCaptcha', array(
                'captchaAction' => '/user/registration/captcha',
                'showRefreshButton'=>false,
                'clickableImage' =>true
            )); 
?><br>
            <?php echo $form->textField($model,'verifyCode'); ?>
            <?php echo $form->error($model,'verifyCode'); ?>
        
            <p class="hint">
                <?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?><br/>
                <?php echo UserModule::t("Letters are not case-sensitive."); ?>
            </p>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="form-label"></div>
        <div class="form-element">
            <?php echo CHtml::submitButton(UserModule::t("Register")); ?>
        </div>
    </div>

    <div class="row">
        <div class="waves"></div>
        <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->