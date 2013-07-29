<?php
    $this->menuTitle = 'Личный кабинет';
    $this->menu=array(
        array('label'=>Yii::t('catalog', 'Orders'), 'url'=>array('/user/order')),
        array('label'=>UserModule::t('Profile'), 'url'=>array('/user/profile')),
        array('label'=>UserModule::t('Edit'), 'url'=>array('edit')),
        array('label'=>UserModule::t('Change password'), 'url'=>array('changepassword')),
        array('label'=>Yii::t('all',  'Subscribe'), 'url'=>array('/user/subscribe')),
        array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
    );
?>