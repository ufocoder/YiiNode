<?php
    $this->title = Yii::t('site', "User list");
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('/admin/user/admin/create'), 'icon'=>'white plus')
    );

    $this->breadcrumbs = array(
        Yii::t('site', 'Users')=>array('/admin/user'),
        Yii::t('site', 'Manage'),
    );
?>
<?php echo $this->renderPartial('/admin/user/_grid', array('model'=>$model)); ?>