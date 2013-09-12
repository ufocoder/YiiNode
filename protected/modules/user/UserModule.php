<?php

class UserModule extends WebModule
{
    const CONFIRM_NONE = 1;
    const CONFIRM_MAIL = 2;

    public $activeAfterRegister = true;
    public $confimTypeRegister = self::CONFIRM_NONE;
    public $allowRegister = true;

    /**
     * Returns the description of this module.
     * @return string the description of this module.
     */
    public function getDescription()
    {
        return '';
    }

}
