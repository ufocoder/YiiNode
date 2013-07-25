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

    Yii::app()->user->setFlash('warning', Yii::t('all', 'Fields with <span class="required">*</span> are required.'));
?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'title', array('class'=>'span6')); ?>
<?php echo $form->textFieldRow($model, 'slug', array('class'=>'span6')); ?>

<?php if ($model->isNewRecord): ?>
<?php if (!empty($nodes)): ?>
<?php echo $form->radioButtonListRow($model, 'node_position', $model->values('position')); ?>
<div class="control-group">
    <?php echo $form->labelEx($model, 'node_related', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbSelect2',array(
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
<?php else: ?>
<div class="control-group">
    <?php echo $form->labelEx($model, 'node_related', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo CHtml::textField(null, Yii::t('site', 'Without related node'), array('disabled'=>'disabled'));?>
    </div>
</div>
<?php endif; ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'module', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbSelect2',array(
            'model' => $model,
            'attribute' => 'module',
            'asDropDownList' => true,
            'options' => array(
                'placeholder' => Yii::t("site", "Select a module"),
                'width' => '328px',
            ),
            'val' => null,
            'data' => $modules,
        )); 
        ?>
    </div>
</div>
<?php endif; ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'content', array('class'=>'control-label')); ?>
    <div class="controls">
    <?php $this->widget('bootstrap.widgets.TbCKEditor', array(
        'model' => $model,
        'attribute' => 'content',
        'editorOptions' => array(
            'language' => Yii::app()->language,
            'toolbar' => array(
                array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
                array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
                array('Link','Unlink','Anchor'),
            )
        )
    )); ?>
    </div>
</div>

<?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?> 

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('all', 'Create') : Yii::t('all', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('all', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>