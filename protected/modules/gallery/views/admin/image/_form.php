<?php
    /* @var $this DefaultController */
    /* @var $model Article */
    /* @var $form BootActiveForm */

    $nodeId = Yii::app()->getNodeId();

    if ($model->isNewRecord)
        $action = Yii::app()->createUrl('default/create', array('nodeAdmin'=>true, 'nodeId'=>$nodeId));
    else
        $action = Yii::app()->createUrl('default/update', array('nodeAdmin'=>true, 'nodeId'=>$nodeId, 'id'=>$model->id_gallery_image));

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

    $categories = array_merge(
        array(Yii::t('site', 'Without category')),
        CHtml::listData(GalleryCategory::model()->node()->findAll(), 'id_gallery_category', 'title')
    );


?>

    <?php echo $form->errorSummary($model); ?>

    <fieldset>
    <legend><?php echo Yii::t('site', 'Common information')?></legend>

    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'id_gallery_category', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php $this->widget('bootstrap.widgets.TbSelect2',array(
                'model' => $model,
                'attribute' => 'id_gallery_category',
                'asDropDownList' => true,
                'options' => array(
                    'width' => '328px',
                    'placeholder' => Yii::t("site", "Select a gallery category"),
                    'allowClear' => true
                ),
                'data' => $categories
            ));
            ?>
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
         <?php if (!$model->isNewRecord && $model->image): ?>
                <p>
                    <div><?php echo CHtml::link(CHtml::image($model->getUploadUrl().$model->image), $model->getUploadUrl().$model->image); ?></div>
                </p>
        <?php endif; ?>
                <?php echo $form->fileField($model, 'x_image', array('attachId'=>$fileID, 'class'=>'attach-input', 'style'=>'display: none;')); ?>
                <?php echo CHtml::textField(null, null, array('attachId'=>$fileID, 'class' => 'attach-name span4', 'readonly'=>'true')); ?>
                <?php echo CHtml::link(Yii::t('site', 'Choose'), '#', array('attachId'=>$fileID, 'class'=>'attach-button btn'));?>
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
                        array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
                        array('Link','Unlink','Anchor'),
                        array('NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
                        array('Source')
                    ),
                    'filebrowserBrowseUrl' => CHtml::normalizeUrl(array("/admin/filemanager/editor"))
                )
            )); ?>
        </div>
    </div>

    <?php echo $form->checkBoxRow($model, 'enabled', $model->isNewRecord?array('checked'=>'checked'):array()); ?>

    </fieldset>


    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->