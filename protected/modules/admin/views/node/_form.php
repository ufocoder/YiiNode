<?php
    /* @var $model Node */
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

<?php echo $form->textFieldRow($model, 'title', array('class'=>'span6')); ?>

<?php if ($model->isNewRecord): ?>
<?php if (!empty($nodes)):
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
<?php echo $form->textFieldRow($model, 'slug', array('class'=>'span6')); ?>
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
<?php echo $form->radioButtonListRow($model, 'node_position', $model->values('position')); ?>
<?php else: ?>
<?php echo $form->textFieldRow($model, 'slug', array('value'=>'/', 'class'=>'span6', 'disabled'=>'disabled')); ?>
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
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>