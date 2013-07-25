<?php
    /* @var $this UserController */
    /* @var $model User */
    /* @var $form BootActiveForm */

    $nodeId = Yii::app()->getNodeId();
    
    if ($model->isNewRecord)
        $action = Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));
    else
        $action = Yii::app()->createUrl('default/update', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id'=>$model->id_article));
    

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'user-form',
        'type'=>'horizontal',
        'action'=>$action,
        'action'=>Yii::app()->createUrl('default/index', array()),
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

<?php echo $action; ?>
    <?php echo $form->errorSummary($model); ?>

    <fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>

    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>
    <?php echo $form->textFieldRow($model, 'slug', array('class'=>'span8')); ?>
    <?php echo $form->textAreaRow($model, 'notice', array('class'=>'span8')); ?>
    <?php echo $form->textAreaRow($model, 'content', array('class'=>'span8')); ?>
    </fieldset>

    <fieldset>
    <legend><?php echo Yii::t('site', 'Meta information')?></legend>
    <?php echo $form->textAreaRow($model, 'meta_keywords', array('class'=>'span8')); ?>
    <?php echo $form->textAreaRow($model, 'meta_description', array('class'=>'span8')); ?>
    </fieldset>

    <div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->