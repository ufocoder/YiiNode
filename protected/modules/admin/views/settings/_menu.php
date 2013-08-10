<?php
    $this->menu = array(
        array('label'=>'Main settings'),
        array('label'=>Yii::t('site', 'Site settings'), 'url'=> Yii::app()->createUrl('/admin/settings'), 'icon'=>'globe'),
    );
?>