<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'setting-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl('default/setting', array('nodeAdmin'=>true, 'nodeId'=> Yii::app()->getNodeId())),
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

<?php echo $form->checkBoxRow($model, 'showDate'); ?>
<?php echo $form->checkBoxRow($model, 'showImageItem'); ?>
<?php echo $form->checkBoxRow($model, 'showImageList'); ?>
<?php echo $form->checkBoxRow($model, 'fieldPosition'); ?>
<?php echo $form->dropDownListRow($model, 'orderPosition', $model::values('orderPosition', 'data')); ?>
<?php echo $form->textFieldRow($model, 'pager', array('class'=>'span6')); ?>
<?php echo $form->checkBoxRow($model, 'rss'); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Save'))); ?>
</div>

<?php $this->endWidget(); ?>