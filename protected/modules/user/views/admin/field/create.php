<?php
    $this->title = Yii::t('site', 'Create profile field');
    $this->breadcrumbs=array(
        Yii::t('site', 'Users') => array('/admin/user/'),
        Yii::t('site', 'Profile fields' )=> array('/admin/user/field'),
        Yii::t('site', 'Create'),
    );
?>
<?php echo $this->renderPartial('/admin/field/_form', array('model'=>$model)); ?>