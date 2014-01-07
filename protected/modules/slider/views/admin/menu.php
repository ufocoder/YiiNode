<?php
    $this->actions = array(
        array('label'=>Yii::t('site', 'Add'), 'url'=>array('/admin/slider/admin/create'), 'icon'=>'plus'),
        array('label'=>Yii::t('slider', 'Slide list'), 'url'=>array('/admin/slider/admin/index'), 'icon'=>'list'),
        array('label'=>Yii::t('site', 'Settings'), 'url'=>array('/admin/slider/admin/setting'), 'icon'=>'cog')
    );
?>