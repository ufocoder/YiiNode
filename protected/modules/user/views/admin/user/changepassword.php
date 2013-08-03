<?php
    /* @var $this AdmiController */
    /* @var $model FormChangePassword */

    $this->title = Yii::t("site", "Change password");
        $this->breadcrumbs = array(
        (Yii::t('site', 'Users'))=>array('/user/admin'),
        CHtml::encode($user->login) => array('view','id'=>$user->id_user),
        Yii::t('site', 'Change password'),
    );

    $this->actions = array(
        array('label'=>Yii::t('site', 'View profile'), 'url'=>array('index', 'id'=>$user->id_user), 'icon'=>'user'),
        array('label'=>Yii::t('site', 'Update user'), 'url'=>array('update', 'id'=>$user->id_user), 'icon'=>'pencil')
    );
?>

<?php
    /* @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'action' => Yii::app()->createUrl($this->route, array('id'=>$user->id_user)),
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

    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span6 text', 'placeholder' => Yii::t('site', 'Enter your new password'))); ?>
    <?php echo $form->passwordFieldRow($model, 'verifyPassword', array('class' => 'span6 text', 'placeholder' => Yii::t('site', 'Enter your new password again'))); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Change'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Yii::t('site', 'Clear'))); ?>
    </div>

<?php $this->endWidget(); ?>