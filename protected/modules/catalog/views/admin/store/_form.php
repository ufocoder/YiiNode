<?php

    $nodeId = Yii::app()->getNodeId();
    if ($model->isNewRecord)
        $action = Yii::app()->createUrl('/store/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));
    else
        $action = Yii::app()->createUrl('/store/update', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id'=>$model->id_store));

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'service-form',
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

    // get Title html ID
    $attribute = 'title';
    $htmlOptions = array();
    CHtml::resolveNameID($model, $attribute, $htmlOptions);
    $titleFieldID = $htmlOptions['id'];

    // get Slug html ID
    $htmlOptions = array();
    $attribute = 'slug';
    CHtml::resolveNameID($model, $attribute, $htmlOptions);
    $slugFieldID = $htmlOptions['id'];

    // register script
    $script = "\$(document).ready(function(){\$('#" . $titleFieldID . "').syncTranslit({destination:'" . $slugFieldID . "'});});";
    Yii::app()->getClientScript()->registerScript('article-form-slug', $script);
?>

<?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span6')); ?>
    <?php echo $form->textFieldRow($model, 'slug', array('class'=>'span6')); ?>

    <?php echo $form->textFieldRow($model, 'alttitle', array('class'=>'span6')); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'notice', array('class'=>'control-label')); ?>
        <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbCKEditor', array(
            'model' => $model,
            'attribute' => 'notice',
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

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>