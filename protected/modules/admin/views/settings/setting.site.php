<?php
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t('site', 'Default settings');
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

    $data_datetimeFormat = $model::values('datetime', 'list');
?>

    <?php echo $form->errorSummary($model); ?>
<fieldset>
    <legend><?php echo Yii::t('site', 'Site description')?></legend>
    <?php echo $form->textFieldRow($model, 'sitename', array('class' => 'span6 text', 'placeholder' => Yii::t('site', 'Enter your sitename'))); ?>
    <?php echo $form->textFieldRow($model, 'emailAdmin', array('class' => 'span6 text', 'placeholder' => Yii::t('site', 'Enter admin e-mail'))); ?>
</fieldset>

<fieldset>
    <legend><?php echo Yii::t('site', 'Datetime format')?></legend>
    <?php echo $form->radioButtonListRow($model, 'datetime', $data_datetimeFormat); ?>
</fieldset>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Change'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>