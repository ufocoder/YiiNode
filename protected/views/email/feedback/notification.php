<?php echo Yii::t("site", "Dear administrator, there is a new feedback request at your website, feedback data is: "); ?><br />
<p>
<b><?php echo Yii::t("site", "Common information");?></b><br />
<?php echo Yii::t("site", "Full name"); ?>: <?php echo $feedback->person_name; ?><br />
<?php echo Yii::t("site", "Contact e-mail"); ?>: <?php echo $feedback->contact_email; ?><br />
<?php echo Yii::t("site", "Contact phone"); ?>: <?php echo $feedback->contact_phone; ?><br />
<?php echo Yii::t("site", "Time created"); ?>: <?php echo date("Y-m-d H:i", $feedback->time_created); ?>
</p>
<p>
<b><?php echo Yii::t("site", "Feedback content");?></b><br />
<?php echo CHtml::encode($feedback->content); ?><br />
</p>