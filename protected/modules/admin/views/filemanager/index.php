<?php
    /* @var $this FilemanagerController */
    $this->title = Yii::t('site', 'File manager');
    $this->pageTitle[] = Yii::t('site', 'File manager');

    $this->breadcrumbs=array(
        Yii::t('site', 'Services') => '#',
        Yii::t('site', 'File manager'),
    );
?>

<div class="alert alert-info">
  <?php echo Yii::t('site', 'File manager could help you upload and edit your files on server.')?>
</div>

<div id="file-uploader"></div>

<?php
    $this->widget('ext.elfinder.ElFinderWidget', array(
        'connectorRoute' => 'admin/filemanager/connector',
    ));
?>