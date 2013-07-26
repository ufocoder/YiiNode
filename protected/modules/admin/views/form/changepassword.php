    <?php
        /* @var BootActiveForm $form */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'action' => Yii::app()->createUrl($this->route, array(
                'activekey' => $activekey,
                'email' => $email,
            )),
            'method' => 'post',
            'clientOptions' => array(
                'validateOnSubmit'=>true,
            )
        ));

        $this->title =  Yii::t('site', 'Change password');
    ?>

<?php
    $error_list = $model->getErrors();
    if (!empty($error_list)):
?>
    <div class="alert alert-error">
    <?php foreach($error_list as $errors): ?>
        <ul>
    <?php foreach($errors as $error): ?>
            <li><?php echo $error; ?></li>
    <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
    </div>
<?php
    endif;
?>
    <?php echo $form->passwordField($model, 'password', array('class' => 'span4 text', 'placeholder' => Yii::t('site', 'Enter your new password'))); ?>
    <?php echo $form->passwordField($model, 'verifyPassword', array('class' => 'span4 text', 'placeholder' => Yii::t('site', 'Enter your new password again'))); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> Yii::t('site', 'Change'))); ?>

    <hr>
    <div style="text-align:center;">
        <?php echo CHtml::link(Yii::t("site", "Authorization"), Yii::app()->user->loginUrl); ?> |
        <?php echo CHtml::link(Yii::t("site", "Go main page"), array('../')); ?>
    </div>

<?php $this->endWidget(); ?>