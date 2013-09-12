<?php
    $this->menu = array(
        array('label'=>Yii::t('site', 'Main settings')),
        array('label'=>Yii::t('site', 'Site settings'), 'url'=> Yii::app()->createUrl('/admin/settings'), 'icon'=>'globe'),
        array('label'=>Yii::t('site', 'User settings'), 'url'=> Yii::app()->createUrl('/admin/settings/user'), 'icon'=>'user'),
    );
?>