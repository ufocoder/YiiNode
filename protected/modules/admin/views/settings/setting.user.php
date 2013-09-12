<?php
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t('site', 'User settings');
    $this->breadcrumbs=array(
        Yii::t("site", "Settings")
    );

    $this->renderPartial('/settings/_menu');

    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit'=>true,
        )
    ));

    $data_confirm = $model::values('confirm', 'list');
?>

    <?php echo $form->errorSummary($model); ?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Registration')?></legend>

    <?php echo $form->checkBoxRow($model, 'userAllowRegister'); ?>
    <?php echo $form->dropDownListRow($model, 'userConfirmTypeRegister', $data_confirm, array('class'=>'span8')); ?>
    <?php echo $form->checkBoxRow($model, 'userActiveAfterRegister'); ?>
</fieldset>


    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Change'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>