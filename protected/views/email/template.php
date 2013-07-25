<html>
<body>
<?php if (!empty($title)):?>
    <h1><?php echo $title; ?></h1>
<?php endif; ?>
    <div class="content">
        <?php echo $content; ?>
    </div>
    <div class="foot-note">
        ---
        <br />
        <?php echo Yii::t('site', 'This was created by robot, there is no need to answer it!');?>
    </div>
</body>
</html>