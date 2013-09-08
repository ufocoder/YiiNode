<?php
    $nodeId = Yii::app()->getNodeId();
    $action = Yii::app()->createUrl('/default/setting', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'setting-form',
        'type'=>'horizontal',
        'action'=>$action,
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

<?php echo $form->textFieldRow($model, 'pager', array('class'=>'span6')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>('Сохранить'))); ?>
</div>

<?php $this->endWidget(); ?>