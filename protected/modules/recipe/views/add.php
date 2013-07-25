<?
    $this->title = Yii::t("recipe", "Add recipe");

    /* @var $this AddController */
    /* @var $model Recipe */
    /* @var $form BootActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'recipe-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
        ),
    ));
?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'title', array('class'=>'span6')); ?>

<div class="control-group ">
    <?php echo $form->labelEx($model,'image', array('class'=>'control-label')); ?>
    <div class="controls">
 <?php if($model->image): ?>
        <p>
            <?php echo CHtml::image($model->getThumbPath('image','small').$model->image); ?><br />
            <?php echo $form->checkBox($model,'delete_image'); ?> <?php echo $form->labelEx($model,'delete_image'); ?>
        </p>
<?php endif; ?>
        <?php echo $form->fileField($model, 'x_image', array('class'=>'span6')); ?>
    </div>
</div>

<?php echo $form->textAreaRow($model, 'notice', array('class'=>'span6')); ?>
<?php echo $form->textAreaRow($model, 'content', array('class'=>'span6')); ?>


<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>