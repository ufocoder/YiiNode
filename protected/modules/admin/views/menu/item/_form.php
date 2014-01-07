<?php
    /* @var $this ItemController */
    /* @var $model MenuList */
    /* @var $form BootActiveForm */


    if ($model->isNewRecord)
        $action = Yii::app()->createUrl($this->route, array('id_menu_list'=>$menu->id_menu_list));
    else
        $action = Yii::app()->createUrl($this->route, array('id_menu_list'=>$menu->id_menu_list, 'id'=>$model->id_menu_item));

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

    $data_node = CHtml::listData(Node::model()->tree()->findAll(), 'id_node', 'title');
?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>
    <?php echo $form->textFieldRow($model, 'alttitle', array('class'=>'span8')); ?>
    <?php echo $form->textFieldRow($model, 'position', array('class'=>'span8')); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'id_node', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php $this->widget('bootstrap.widgets.TbSelect2',array(
                'model' => $model,
                'attribute' => 'id_node',
                //'asDropDownList' => false,
                'data' => $data_node,
                'htmlOptions'=>array(
                    'class'=>'span8'
                )
            )); ?>
        </div>
    </div>

    <?php echo $form->textFieldRow($model, 'url', array('class'=>'span8')); ?>

    <?php
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
            $('.attach-name[attachId='+id+']').val(file[file.length-1]);
        });
    });";
        Yii::app()->clientScript->registerScript('form-file', $script);
    ?>

    <?php $fileID = substr(md5(time().rand()%39), 0, 8); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'icon', array('class'=>'control-label')); ?>
        <div class="controls">
         <?php if (!$model->isNewRecord && $model->image): ?>
                <p>
                    <div><?php echo CHtml::link(CHtml::image($model->icon), $model->icon); ?></div>
                    <div><?php echo $form->checkBox($model, 'delete_icon', array('style' => 'float: left; margin-right: 5px;;')); ?> <?php echo $form->labelEx($model,'delete_icon'); ?></div>
                </p>
        <?php endif; ?>
                <?php echo $form->fileField($model, 'x_icon', array('attachId'=>$fileID, 'class'=>'attach-input', 'style'=>'display: none;')); ?>
                <?php echo CHtml::textField(null, null, array('attachId'=>$fileID, 'class' => 'attach-name span4', 'readonly'=>'true')); ?>
                <?php echo CHtml::link(Yii::t('site', 'Choose'), '#', array('attachId'=>$fileID, 'class'=>'attach-button btn'));?>
        </div>
    </div>

    <?php $fileID = substr(md5(time().rand()%37), 0, 8); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'image', array('class'=>'control-label')); ?>
        <div class="controls">
         <?php if (!$model->isNewRecord && $model->image): ?>
                <p>
                    <div><?php echo CHtml::link(CHtml::image($model->image), $model->image); ?></div>
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
        <div class="span8">
            <?php $this->widget('bootstrap.widgets.TbCKEditor', array(
                'model' => $model,
                'attribute' => 'notice',
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