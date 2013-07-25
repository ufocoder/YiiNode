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

<?php echo $form->textFieldRow($model, 'username', array('class'=>'input-small', 'prepend'=>'<i class="icon-search"></i>')); ?> 
<?php echo $form->textFieldRow($model, 'email', array('class'=>'input-small span3')); ?> 
<?php echo $form->dropDownList($model, 'superuser', $model->itemAlias('AdminStatus'), array('empty' => Yii::t('all', 'Administrator'), 'class'=>'input-small span3')); ?> 
<?php echo $form->dropDownList($model, 'status', $model->itemAlias('UserStatus'), array('empty' => Yii::t('all', 'Status'), 'class'=>'input-small span2')); ?> 

<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Yii::t('all', 'Search'))); ?>

<?php $this->endWidget(); ?>