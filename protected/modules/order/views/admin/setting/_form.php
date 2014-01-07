<?php

    $action = Yii::app()->createUrl('/admin/order/setting/index');

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'setting-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'post',
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        ),
    ));
?>
<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'orderDeliveryPrice', array('class'=>'span5')); ?>
<?php echo $form->textFieldRow($model, 'orderNoticeEmail', array('class'=>'span5')); ?>
<?php echo $form->checkBoxRow($model, 'orderNoticeAdmin'); ?>
<?php echo $form->checkBoxRow($model, 'orderNoticeManager'); ?>
<?php echo $form->checkBoxRow($model, 'orderNoticeUser'); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>Yii::t('site', 'Save'))); ?>
</div>

<?php $this->endWidget(); ?>