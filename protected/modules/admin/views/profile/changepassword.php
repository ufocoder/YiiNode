<?php
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t("site", "Change password");
    $this->breadcrumbs=array(
        Yii::t("site", "Profile") => array('/'.$this->module->id.'/profile'),
        Yii::t('site', 'Change password')
    );
?>

<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions' => array(
            'class' => 'well'
        )
    ));
?>

    <?php echo $form->errorSummary($model); ?>
    
    <?php echo $form->passwordFieldRow($model, 'oldPassword', array('class' => 'span6 text', 'placeholder' => Yii::t('site', 'Enter your old password'))); ?>
    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span6 text', 'placeholder' => Yii::t('site', 'Enter your new password'))); ?>
    <?php echo $form->passwordFieldRow($model, 'verifyPassword', array('class' => 'span6 text', 'placeholder' => Yii::t('site', 'Enter your new password again'))); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Change'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>
    

<?php $this->endWidget(); ?>
</div><!-- form -->