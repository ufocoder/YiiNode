<?php

    $action = Yii::app()->createUrl('/admin/order/default/update', array('id'=>$model->id_order));

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
<?php echo $form->dropDownListRow($model, 'id_order_status', OrderStatus::getStatus(), array('class'=>'span6')); ?>
<?php echo $form->textAreaRow($model, 'comment', array('class'=>'span6', 'rows'=>4, 'value'=>'')); ?>
<?php echo $form->checkBoxRow($model, 'sendNotice', array()); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>Yii::t('site', 'Save'))); ?>
</div>

<?php $this->endWidget(); ?>