<?php
    $this->pageTitle = Yii::t("site", "User login");
    $this->breadcrumbs=array(
        Yii::t("site", "Login"),
    );

?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <div class="form-label">
            <?php echo CHtml::activeLabelEx($model,'login'); ?>
        </div>
        <div class="form-element">
            <?php echo CHtml::activeTextField($model, 'login') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="form-label">
            <?php echo CHtml::activeLabelEx($model,'password'); ?>
        </div>
        <?php 
        echo "test:". Yii::app()->createNodeUrl(Yii::app()->getNodeID(), "registration/index");
        ?>
        <div class="form-element">
            <?php echo CHtml::activePasswordField($model,'password') ?>
            <p class="hint">
            <?php echo CHtml::link(Yii::t("site", "Register"), Yii::app()->createNodeUrl(Yii::app()->getNodeID(), "user/registration")); ?> | <?php echo CHtml::link(Yii::t("site", "Lost Password?"),Yii::app()->user->recoveryUrl); ?>
            </p>
        </div>
    </div>
    
    <div class="row">
    <?php $this->widget('CCaptcha', array(
            'captchaAction' => '/admin/login/captcha', 
            'showRefreshButton'=>false,
            'clickableImage' =>true, 
            'imageOptions' => array(
                'class' => 'captcha',
            ))
        ); 
    ?>
    <?php echo CHtml::activeTextField($model, 'verifyCode', array('class'=>'span2 captcha-input', 'placeholder' => Yii::t('site', 'Enter code')))?>
    </div>

    <div class="row">
        <div class="form-label"></div>
        <div class="form-element">
            <?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
            <?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
        </div>
    </div>

    <div class="row">
        <div class="form-label"></div>
        <div class="form-element">
            <?php echo CHtml::submitButton(Yii::t("site", "Login")); ?>
        </div>
    </div>

    <div class="row">
        <div class="waves"></div>
        <p class="note"><?php echo Yii::t("site", 'Fields with <span class="required">*</span> are required.'); ?></p>
    </div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->