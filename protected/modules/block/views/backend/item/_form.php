<?php 
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'simpleblock-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route, ((!empty($model->isNewRecord)&&isset($model->isNewRecord))?array():array('id'=>$model->id))),
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

<?php echo $form->textFieldRow($model, 'title', array('class'=>'span6', 'disabled'=>'disabled')); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'content', array('class'=>'control-label')); ?>
    <div class="controls">
<?php $this->widget('application.extensions.ckeditor.CKeditor', array(
                'model'=>$model,
                'attribute'=>'content',
                'config' => Yii::app()->params['ckeditor']['medium'],
)); ?>
    </div>
</div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? 'Создать' : 'Сохранить'))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Обнулить')); ?>
</div>

<?php $this->endWidget(); ?>