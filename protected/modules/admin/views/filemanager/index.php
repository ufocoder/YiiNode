<?php
    /* @var $this FilemanagerController */
    $this->title = Yii::t('site', 'Filemanager');
    $this->pageTitle[] = Yii::t('site', 'Filemanager');

    $this->breadcrumbs=array(
        Yii::t('site', 'Services') => '#',
        Yii::t('site', 'Filemanager'),
    );
?>

<div class="alert alert-info">
  <?php echo Yii::t('site', 'Filemanager could help you upload and edit your files on server.')?>
</div>

<div id="file-uploader"></div>

<?php
    $this->widget('ext.elfinder2.ElFinderWidget', array(
        'connectorRoute' => 'admin/filemanager/connector',
    ));
?>