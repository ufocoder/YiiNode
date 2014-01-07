<?php
    /* @var $form CActiveForm */
    $this->pageTitle = Yii::t('order', 'Basket');
    $this->breadcrumbs = array(
        Yii::t('order', 'Basket') => array('/order'),
        Yii::t('order', 'Ordering')
    );

    if (!isset($actionUrl))
        $actionUrl = null;
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'basket-form',
    'action' => $actionUrl,
    'enableAjaxValidation'=>false,
)); ?>

    <?php echo $form->errorSummary($model); ?>

<?php if (Yii::app()->user->isGuest):?>
    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'person_name'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->textField($model,'person_name',array('size'=>50,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'person_name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'contact_phone'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->textField($model,'contact_phone',array('size'=>50,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'contact_phone'); ?>
        </div>
    </div>

    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'contact_email'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->textField($model,'contact_email',array('size'=>50,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'contact_email'); ?>
        </div>
    </div>
<?php  else: ?>

    <div class="line-solid"></div>

    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'delivery_type'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->radioButtonList($model,'delivery_type', array('1'=>'на дом','2'=>'в офис')); ?>
            <?php echo $form->error($model,'delivery_type'); ?>
        </div>
    </div>

<?php endif; ?>
    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'delivery_addr'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->textArea($model,'delivery_addr',array('rows'=>6, 'cols'=>50)); ?>
            <?php echo $form->error($model,'delivery_addr'); ?>
        </div>
    </div>

    <div class="row">
        <div class="form-label">
            <?php echo $form->labelEx($model,'comment'); ?>
        </div>
        <div class="form-element">
            <?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
            <?php echo $form->error($model,'comment'); ?>
        </div>
    </div>

<?if (extension_loaded('gd') && Yii::app()->user->isGuest):?>
    <div class="row">
        <div class="form-label">
            <?php echo CHtml::activeLabelEx($model, 'verifyCode')?>
        </div>
        <div class="form-element">
            <?$this->widget('CCaptcha', array('captchaAction' => '/order/send/captcha','showRefreshButton'=>false,
                    'clickableImage' =>true)); ?>
            <?php echo CHtml::activeTextField($model, 'verifyCode')?>
        </div>
    </div>
<?endif?>

    <div class="row">
        <div class="form-label">
        </div>
        <div class="form-element">
            <p class="note"><?php echo Yii::t('order', 'Fields with <span class="required">*</span> are required.'); ?></p>
            <?php echo CHtml::submitButton(Yii::t('order', 'Send')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->