<?php

class UserModule extends WebModule {

    static private $_users = array();
    static private $_userByName = array();

    // @TODO:
    public $activeAfterRegister = false;
    public $sendActivationMail = false;
    public $autoLogin = true;
    public $loginNotActiv = true;

    /**
     * Return safe user data.
     * @param user id not required
     * @return user object or false
     */
    public static function user($id = 0, $clearCache = false) {
        if (!$id && !Yii::app()->user->isGuest)
            $id = Yii::app()->user->id;
        if ($id) {
            if (!isset(self::$_users[$id]) || $clearCache)
                self::$_users[$id] = User::model()->with(array('profile'))->findbyPk($id);
            return self::$_users[$id];
        } else
            return false;
    }

    /**
     * Return safe user data.
     * @param user name
     * @return user object or false
     */
    public static function getUserByName($username) {
        if (!isset(self::$_userByName[$username])) {
            $_userByName[$username] = User::model()->findByAttributes(array('username' => $username));
        }
        return $_userByName[$username];
    }

}
