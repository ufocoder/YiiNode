<?php 
    /* @var $this ProfileController */
    /* @var $model User */

    $this->title = Yii::t('site', 'Update');
    $this->breadcrumbs=array(
        Yii::t("site", "Profile") => array('/'.$this->module->id.'/profile'),
        Yii::t("site", "Update")
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View profile'), 'url'=>array('index')),
        array('label'=>Yii::t('site', 'Change password'), 'url'=>array('changepassword')),
    );

    $this->title = Yii::t("site", "Update profile");
?>
<?php

    /* @var $this UserController */
    /* @var $model User */
    /* @var $form BootActiveForm */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'user-form',
        'type'=>'horizontal',
        'action'=>Yii::app()->createUrl($this->route, (!empty($model->isNewRecord)?array():array('id'=>$model->id_user))),
        'method'=>'post',
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'enctype' => 'multipart/form-data',
            'class'=>'well'
        ),
    ));
?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'login', array('class'=>'span6')); ?>

<?php if ($model->isNewRecord): ?>
    <?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span6')); ?>
    <?php echo $form->passwordFieldRow($model, 'verifyPassword', array('class'=>'span6')); ?>
    <?php echo $form->dropDownListRow($model, 'role', User::values('role')); ?>
<?php endif; ?>

    <?php echo $form->textFieldRow($model, 'email', array('class'=>'span6')); ?>

    <div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>($model->isNewRecord ? Yii::t('site', 'Create') : Yii::t('site', 'Save')))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->