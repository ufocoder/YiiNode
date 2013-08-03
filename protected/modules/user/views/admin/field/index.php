<?php
    $this->title = Yii::t('site', 'Profile field list');
    $this->titleButton = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=> Yii::app()->createUrl('/admin/user/field/create'), 'icon'=>'white plus')
    );
    $this->breadcrumbs = array(
        Yii::t('site', 'Users') => array('/admin/user'),
        Yii::t('site', 'Profile fields'),
    );
?>
<?php echo $this->renderPartial('/admin/field/_grid', array('model'=>$model)); ?>
