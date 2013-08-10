<?php

class UserModule extends WebModule
{

    // @TODO:
    public $activeAfterRegister = true;
    public $sendActivationMail = false;
    public $autoLogin = true;
    public $loginNotActiv = true;

    /**
     * Returns the description of this module.
     * @return string the description of this module.
     */
    public function getDescription()
    {
        return '';
    }

}
