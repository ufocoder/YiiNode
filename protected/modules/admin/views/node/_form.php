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

    $theme_layouts = array();
    $node_layouts = array();

    if (Yii::app()->Theme)
        $theme_layouts = Yii::app()->Theme->getSetting('layouts');

    foreach ($theme_layouts as $layout => $params)
        $node_layouts[$layout] = $params['label'];

?>

<?php echo $form->errorSummary($model); ?>


<fieldset>
    <legend><?php echo Yii::t('site', 'Common information');?></legend>
    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>
    <?php if (!$model->isRoot() && (!empty($nodes) || !$model->isNewRecord)):
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
    <?php echo $form->textFieldRow($model, 'slug', array('class'=>'span8')); ?>
    <?php endif; ?>

    <?php if ($model->isNewRecord): ?>
    <?php if (!empty($nodes)): ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'node_related', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php $this->widget('bootstrap.widgets.TbSelect2',array(
                'model' => $model,
                'attribute' => 'node_related',
                'asDropDownList' => true,
                'options' => array(
                    'placeholder' => Yii::t("site", "Select a node"),
                    'width' => '370px',
                ),
                'val' => null,
                'data' => $nodes,
            )); ?>
        </div>
    </div>
    <?php echo $form->radioButtonListRow($model, 'node_position', $model->values('position')); ?>
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
                    'width' => '370px',
                ),
                'val' => null,
                'data' => $modules,
            ));
            ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($node_layouts)): ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'layout', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php $this->widget('bootstrap.widgets.TbSelect2',array(
                'model' => $model,
                'attribute' => 'layout',
                'asDropDownList' => true,
                'options' => array(
                    'placeholder' => Yii::t("site", "Select a module"),
                    'width' => '370px',
                ),
                'val' => null,
                'data' => $node_layouts,
            ));
            ?>
        </div>
    </div>
    <?php endif; ?>

    <?php echo $form->textAreaRow($model, 'description', array('class'=>'span8', 'rows'=>7, 'hint'=>Yii::t('site', 'Node description for administration or site manager'))); ?>

    <?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?>
    <?php echo $form->checkBoxRow($model, 'hidden'); ?>
</fieldset>

<fieldset>
    <legend><?php echo Yii::t('site', 'Meta information')?></legend>
    <?php echo $form->textFieldRow($model, 'meta_title', array('class'=>'span8')); ?>
    <?php echo $form->textAreaRow($model, 'meta_keywords', array('class'=>'span8', 'style'=>'min-height: 80px;')); ?>
    <?php echo $form->textAreaRow($model, 'meta_description', array('class'=>'span8', 'style'=>'min-height: 80px;')); ?>
</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>