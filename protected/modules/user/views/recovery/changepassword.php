<?php 
    $this->pageTitle = UserModule::t("Change Password");
    $this->breadcrumbs=array(
        UserModule::t("Profile"),
    );
?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

    <?php echo CHtml::errorSummary($form); ?>
    
    <div class="row">
        <div class="form-label">
            <?php echo CHtml::activeLabelEx($form,'password'); ?>
        </div>
        <div class="form-element">
            <?php echo CHtml::activePasswordField($form,'password'); ?>
            <p class="hint"><?php echo UserModule::t("Minimal password length 4 symbols."); ?></p>
       </div>
    </div>
    
    <div class="row">
        <div class="form-label">
            <?php echo CHtml::activeLabelEx($form,'verifyPassword'); ?>
        </div>
        <div class="form-element">
            <?php echo CHtml::activePasswordField($form,'verifyPassword'); ?>
        </div>
    </div>

    <div class="row">
        <div class="form-label"></div>
        <div class="form-element">
            <?php echo CHtml::submitButton(UserModule::t("Save")); ?>
        </div>
    </div>

    <div class="row">
        <div class="waves"></div>
        <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
    </div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->