<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'service-search',
        'type'=>'inline',
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'post',
        'htmlOptions'=>array(
            'class'=>'well'
        ),
    ));
?>

<?php echo $form->textFieldRow($model, 'title', array('class'=>'input-medium', 'prepend'=>'<i class="icon-search"></i>')); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Yii::t('site', 'Search'))); ?>

<?php $this->endWidget(); ?>