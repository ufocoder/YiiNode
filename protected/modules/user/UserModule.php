<?php

class UserModule extends WebModule
{
    const CONFIRM_NONE = 1;
    const CONFIRM_MAIL = 2;

    /**
     * Flag module is node type
     */
    public $nodeModule = false;


    public $activeAfterRegister = false;
    public $confimTypeRegister = self::CONFIRM_NONE;
    public $allowRegister = false;

    /**
     * Module initialization
     */
    public function init()
    {
        parent::init();

        // Import module models and components
        $this->setImport(array(
            'admin.components.*',
            'admin.models.*',
        ));

        // redefine user url list
        Yii::app()->user->recoveryUrl = array('/user/recovery');
        Yii::app()->user->loginUrl = array('/user/login');
        Yii::app()->user->returnUrl = array('/user');
        Yii::app()->user->returnLogoutUrl = array('/user/login');
    }

    /**
     * Returns the description of this module.
     * @return string the description of this module.
     */
    public function getDescription()
    {
        return '';
    }

}
