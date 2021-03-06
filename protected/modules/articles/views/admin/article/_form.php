<?php
    /* @var $this DefaultController */
    /* @var $model Article */
    /* @var $form BootActiveForm */

    $nodeId = Yii::app()->getNodeId();

    if ($model->isNewRecord)
        $action = Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));
    else
        $action = Yii::app()->createUrl('default/update', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id'=>$model->id_article));

    $fieldPosition = Yii::app()->getNodeSetting($nodeId, 'fieldPosition', ArticleSetting::values('fieldPosition', 'default'));

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'articles-form',
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

    $data_tags = CHtml::listData(ArticleTag::model()->node()->findAll(), 'id_article_tag', 'title');

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

    <fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>

    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>
    <?php echo $form->textFieldRow($model, 'slug', array('class'=>'span8')); ?>



    <?php echo $form->checkBoxListRow($model, 'tag_list', $data_tags, array()); ?>



<?php if ($fieldPosition): ?>
    <?php echo $form->textFieldRow($model, 'position', array('class'=>'span8')); ?>
<?php endif; ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'time_published', array('class'=>'control-label')); ?>
        <div class="controls">
        <?php
            $default = SettingDefault::values('datetime', 'default');
            $defaultFormat = SettingDefault::values('datetimeFormat', 'default');
            $settingFormat = Yii::app()->getSetting('datetime', $default);
            $dataFormat = SettingDefault::values('datetimeFormat', 'list', $settingFormat);

            $this->widget('ext.datetimepicker.DatetimePickerWidget', array(
                'model' => $model,
                'attribute' => 'date_published',
                'dataFormat' => $dataFormat
        ));?>
        </div>
    </div>

    <?php
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
        <?php if (!$model->isNewRecord && $model->image):
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

    <?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?>

    </fieldset>

    <fieldset>
        <legend><?php echo Yii::t('site', 'Meta information')?></legend>
        <?php echo $form->textFieldRow($model, 'meta_title', array('class'=>'span8')); ?>
        <?php echo $form->textAreaRow($model, 'meta_keywords', array('class'=>'span8')); ?>
        <?php echo $form->textAreaRow($model, 'meta_description', array('class'=>'span8')); ?>
    </fieldset>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>