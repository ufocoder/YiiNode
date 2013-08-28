<?php

    $nodeId = Yii::app()->getNodeId();
    $this->breadcrumbs = array(
        Yii::t('site', 'Content')
    );

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'page-form',
        'action'=>Yii::app()->createUrl('default/index', array('nodeAdmin'=>true, 'nodeId'=>$nodeId)),
        'method'=>'post',
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        )
    ));
?>

<?php echo $form->errorSummary($model); ?>

<div class="control-group">
    <?php echo CHtml::activeLabelEx($model, 'title', array('style'=>'font-size: 14px; line-height: 22px;')); ?>
    <div class="controls">
        <?php echo $form->textField($model, 'title', array('class'=>'span12')); ?>
    </div>
</div>

<div class="control-group">
    <?php echo CHtml::activeLabelEx($model, 'content', array('style'=>'font-size: 14px; line-height: 22px;')); ?>
    <div class="controls">
    <?php $this->widget('bootstrap.widgets.TbCKEditor', array(
        'model' => $model,
        'attribute' => 'content',
        'editorOptions' => array(
            'language' => Yii::app()->language,
            'toolbar' => array(
                array('Source','-','Preview','Templates','Print'),
                array('Maximize', 'ShowBlocks'),
                array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
                array('Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt'),
                '/',
                array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
                array('Link','Unlink','Anchor'),
                array('NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'),
                '/',
                array('Styles','Format','Font','FontSize'),
                array('TextColor','BGColor'),
                array('Image','Flash','Table','HorizontalRule','SpecialChar','PageBreak','Iframe')
            ),
            'filebrowserBrowseUrl' => CHtml::normalizeUrl(array("/admin/filemanager/editor"))
        )
    )); ?>
    </div>
</div>
<div class="form-actions" style="padding-left: 0; margin-bottom: 0; ">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>
<?php $this->endWidget(); ?>