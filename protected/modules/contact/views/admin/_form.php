<?php

    $nodeId = Yii::app()->getNodeId();

    if ($model->isNewRecord)
        $action = Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));
    else
        $action = Yii::app()->createUrl('default/update', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id'=>$model->id_contact));

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'contact-form',
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
?>

<fieldset>
<legend><?php echo Yii::t('site', 'Content'); ?></legend>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'content', array('class'=>'control-label')); ?>
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
        <?php if($model->image):
                $image = $model->image;
                $thumb = Yii::app()->image->thumbSrcOf($image, array('resize' => array('width' => 350)));
        ?>
                <p>
                    <div><?php echo CHtml::link(CHtml::image($thumb), $image); ?></div>
                    <div><?php echo $form->checkBox($model,'delete_image', array('style' => 'float: left; margin-right: 5px;;')); ?> <?php echo $form->labelEx($model,'delete_image'); ?></div>
                </p>
        <?php endif; ?>
                <?php echo $form->fileField($model, 'x_image', array('attachId'=>$fileID, 'class'=>'attach-input', 'style'=>'display: none;')); ?>
                <?php echo CHtml::textField(null, null, array('attachId'=>$fileID, 'class' => 'attach-name span4', 'readonly'=>'true')); ?>
                <?php echo CHtml::link(Yii::t('site', 'Choose'), '#', array('attachId'=>$fileID, 'class'=>'attach-button btn'));?>
        </div>
    </div>

<?php echo $form->textFieldRow($model, 'timework', array('class'=>'span8')); ?>
<?php echo $form->textFieldRow($model, 'phone', array('class'=>'span8')); ?>
<?php echo $form->textFieldRow($model, 'email', array('class'=>'span8')); ?>
<?php echo $form->textFieldRow($model, 'icq', array('class'=>'span8')); ?>
<?php echo $form->textFieldRow($model, 'skype', array('class'=>'span8')); ?>
<?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?>
</fieldset>

<fieldset>
    <legend><?php echo Yii::t('site', 'Address and coordinates')?></legend>
    <div class="alert alert-info"><?php echo Yii::t('site', 'Click to map area to set coordinates.');?></div>
    <?php echo $form->hiddenField($model, 'map_lat', array('class'=>'span8', 'id'=>'map_lat')); ?>
    <?php echo $form->hiddenField($model, 'map_long', array('class'=>'span8', 'id'=>'map_long')); ?>
    <?php echo $form->hiddenField($model, 'map_zoom', array('class'=>'span8', 'id'=>'map_zoom')); ?>
    <div class="control-group">
        <?php echo $form->textField($model, 'addr', array('class'=>'span12', 'id'=>'map_addr')); ?>
        <?php $widgetParams = array(
                'containerId' => 'ymap',
                'htmlOptions'=>array(
                    'style'=> 'width:100%; height: 300px; margin: 10px 0;'
                ),
                'addressId' => 'map_addr',
                'latId'     => 'map_lat',
                'lonId'     => 'map_long',
            );

            if (!empty($model->lat) && !empty($model->lon))
                $widgetParams['mapPoint'] = array($model->lat, $model->lon);

            $this->widget('ext.maps.widgets.YandexMapPoint', $widgetParams);
        ?>
    </div>
</fieldset>


<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
</div>

<?php $this->endWidget(); ?>