<?php
	/* @var $this ListController */
	/* @var $model MenuList */
	/* @var $form BootActiveForm */

	if ($model->isNewRecord)
		$action = Yii::app()->createUrl($this->route);
	else
		$action = Yii::app()->createUrl($this->route, array('id'=>$model->id_menu_list));

	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'page-form',
		'type'=>'horizontal',
		'action'=> $action,
		'method'=>'post',
		'clientOptions'=>array(
				'validateOnSubmit'=>true,
		),
		'htmlOptions'=>array(
			'enctype' => 'multipart/form-data',
		),
	));
?>

	<?php echo $form->textFieldRow($model, 'title', array('class'=>'span8')); ?>
    <?php
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
        Yii::app()->getClientScript()->registerScript('menu-form-slug', $script);

    ?>
    <?php echo $form->textFieldRow($model, 'slug', array('class'=>'span8')); ?>

	<div class="control-group">
		<?php echo $form->labelEx($model,'notice', array('class'=>'control-label')); ?>
		<div class="controls">
			<?php $this->widget('bootstrap.widgets.TbCKEditor', array(
				'model' => $model,
				'attribute' => 'notice',
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

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
	</div>

<?php $this->endWidget(); ?>