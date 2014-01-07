<?php

    $action = Yii::app()->createUrl('/admin/order/user/update', array('id_user'=>$model->id_user));

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'order-form',
        'type'=>'horizontal',
        'action'=>$action,
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        )
    ));
?>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->textFieldRow($model, 'discount', array('class'=>'span5')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>Yii::t('site', 'Save'))); ?>
</div>

<?php $this->endWidget(); ?>