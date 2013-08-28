<?php
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t("site", "Change password");
    $this->breadcrumbs=array(
        Yii::t("site", "Profile") => array('/'.$this->module->id.'/profile'),
        Yii::t('site', 'Change password')
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View profile'), 'url' => array('index'), 'icon'=>'user'),
        array('label'=>Yii::t('site', 'Update profile'), 'url' => array('update'), 'icon'=>'pencil'),
    );
?>

<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('CActiveForm', array(
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

    <div class="control-group">
        <?php echo $form->labelEx($model, 'oldPassword'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'oldPassword', array('placeholder' => Yii::t('site', 'Enter your old password'))); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'password'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'password', array('placeholder' => Yii::t('site', 'Enter your new password'))); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'verifyPassword'); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'verifyPassword', array('placeholder' => Yii::t('site', 'Enter your new password again'))); ?>
        </div>
    </div>

    <div class="form-actions">
        <?php echo CHtml::button(Yii::t('site', 'Change'), array('type'=>'submit')); ?>
        <?php echo CHtml::button(Yii::t('site', 'Clear'), array('type'=>'reset')); ?>
    </div>

<?php $this->endWidget(); ?>