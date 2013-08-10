<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'service-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route, (!empty($model->isNewRecord)?array():array('id'=>$model->id_node))),
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

<?php if (!empty($nodes)): ?>
<div class="control-group">
    <?php echo $form->labelEx($model, 'node_related', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbSelect2', array(
            'model' => $model,
            'attribute' => 'node_related',
            'asDropDownList' => true,
            'options' => array(
                'placeholder' => Yii::t("site", "Select a node"),
                'width' => '328px',
            ),
            'val' => null,
            'data' => $nodes,
        ));
        ?>
    </div>
</div>
<?php echo $form->radioButtonListRow($model, 'node_position', $model->values('position')); ?>
<?php else: ?>
<div class="control-group">
    <?php echo $form->labelEx($model, 'node_related', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo CHtml::textField(null, Yii::t('site', 'Without related node'), array('disabled'=>'disabled'));?>
    </div>
</div>
<?php endif; ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>