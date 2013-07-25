<?php 
    $this->pageTitle = UserModule::t("Restore");
    $this->breadcrumbs=array(
        UserModule::t("Profile")
    );
?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($form); ?>
    
    <div class="row">
        <div class="form-label">
            <?php echo CHtml::activeLabel($form,'login_or_email'); ?>
        </div>
        <div class="form-element">
            <?php echo CHtml::activeTextField($form,'login_or_email') ?>
            <p class="hint"><?php echo UserModule::t("Please enter your login or email addres."); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="form-label"></div>
        <div class="form-element">
            <?php echo CHtml::submitButton(UserModule::t("Restore")); ?>
        </div>
    </div>


<?php echo CHtml::endForm(); ?>
</div><!-- form -->