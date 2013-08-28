<?php
	/* @var $this BlockController */
	/* @var $model Block */
	/* @var $form BootActiveForm */

	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'page-form',
		'type'=>'horizontal',
		'action'=> Yii::app()->createUrl($this->route, array('id'=>$model->id_block)),
		'method'=>'post',
		'clientOptions'=>array(
				'validateOnSubmit'=>true,
		),
		'htmlOptions'=>array(
			'enctype' => 'multipart/form-data',
		),
	));
?>

	<?php echo $form->textFieldRow($model, 'title', array('class'=>'span8', 'readonly'=>'true')); ?>

<?php switch($model->type):
	default:
	case $model::TYPE_STRING:
?>
	<?php echo $form->textFieldRow($model, 'content', array('class'=>'span10')); ?>
<?php break; case $model::TYPE_TEXT: ?>
	<?php echo $form->textAreaRow($model, 'content', array('class'=>'span10', 'rows'=>6)); ?>
<?php break; case $model::TYPE_HTML: ?>
	<div class="control-group">
		<?php echo $form->labelEx($model,'content', array('class'=>'control-label')); ?>
		<div class="controls">
			<?php $this->widget('bootstrap.widgets.TbCKEditor', array(
				'model' => $model,
				'attribute' => 'content',
				'editorOptions' => array(
					'toolbar' => array(
						array('Styles','Format','Font','FontSize'),
						array('Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'),
						'/',
						array('NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
						array('Image','Table','-','Link','Flash','Smiley','TextColor','BGColor','Source')
					)
				))
			); ?>
		</div>
	</div>
<?php
	break;
	case $model::TYPE_FILE:
	case $model::TYPE_IMAGE:
	case $model::TYPE_FLASH:
?>
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
		Yii::app()->clientScript->registerScript('block-file', $script);
	?>
<div class="control-group">
	<?php echo $form->labelEx($model,'content', array('class'=>'control-label')); ?>
	<div class="controls">
			<?php echo $form->fileField($model, 'content', array('attachId'=>$fileID, 'class'=>'attach-input', 'style'=>'display: none;')); ?>
			<?php echo CHtml::textField(null, $model->content, array('attachId'=>$fileID, 'class' => 'attach-name span4', 'readonly'=>'true')); ?>
			<?php echo CHtml::link(Yii::t('site', 'Choose'), '#', array('attachId'=>$fileID, 'class'=>'attach-button btn'));?>
		<?php if (!empty($model->content)): ?>
			<?php echo CHtml::link(Yii::t('site', 'Download file'), $model->getUploadUrl().$model->content); ?>
		<?php endif;?>
	</div>
</div>
<?php break; endswitch; ?>


	<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->