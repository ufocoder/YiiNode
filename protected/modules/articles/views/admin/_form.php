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

    <fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>

    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>
    <?php echo $form->textFieldRow($model, 'slug', array('class'=>'span8')); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'time_published', array('class'=>'control-label')); ?>
        <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model'=>$model,
            'attribute'=>'time_published',
            'options'=>array(
                'showAnim'=>'fold',
            ),
            'htmlOptions'=>array(
                'style'=>'height:20px;'
            ),
        ));?>
        </div>
    </div>
    <?php echo $form->textFieldRow($model, 'time_published', array('class'=>'span8')); ?>

    <?php
        $attributeFile = get_class($model).'[filename]';
        $fileID = substr(md5(time()), 0, 8);
        $script = "$(document).ready(function(){
        $('.attach-button').live('click', function(){
            var id = $(this).attr('attachId');
            $('.attach-input[attachId='+id+']').trigger('click');
            return false;
        });

        $('.attach-input').live('change', function(e){
            var id = $(this).attr('attachId'),
                val = $(this).val(),
                file = val.split(/[\\\]/);
            console.log(file);
            $('.attach-name[attachId='+id+']').val(file[file.length-1]);
        });
    });";
        Yii::app()->clientScript->registerScript('form-file', $script);
    ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'image', array('class'=>'control-label')); ?>
        <div class="controls">
         <?php if($model->image): ?>
                <p>
                    <div><?php echo CHtml::link(CHtml::image($model->getUploadUrl().$model->image), $model->getUploadUrl().$model->image); ?></div>
                    <div><?php echo $form->checkBox($model,'delete_image', array('style' => 'float: left; margin-right: 5px;;')); ?> <?php echo $form->labelEx($model,'delete_image'); ?></div>
                </p>
        <?php endif; ?>
                <?php echo $form->fileField($model, 'x_image', array('attachId'=>$fileID, 'class'=>'attach-input', 'style'=>'display: none;')); ?>
                <?php echo CHtml::textField(null, null, array('attachId'=>$fileID, 'class' => 'attach-name span4', 'readonly'=>'true')); ?>
                <?php echo CHtml::link(Yii::t('site', 'Choose'), '#', array('attachId'=>$fileID, 'class'=>'attach-button btn'));?>
        </div>
    </div>


    <?php echo $form->textAreaRow($model, 'notice', array('class'=>'span8', 'style'=>'min-height: 90px;')); ?>

    <div class="control-group">
        <?php echo $form->label($model, 'content', array('class'=>'control-label')); ?>
        <div class="span8">
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

    <?php echo $form->checkBoxRow($model, 'enabled'); ?>

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