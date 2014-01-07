<?php
/**
 * Web user
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class WebUser extends CWebUser
{
    /**
     * Roles
     */
    const ROLE_ADMIN        = 'admin';
    const ROLE_MODERATOR    = 'moderator';
    const ROLE_MANAGER      = 'manager';
    const ROLE_USER         = 'user';
    const ROLE_GUEST        = 'guest';

    /**
     * user model
     */
    private $_model = null;

    /**
     * Remember me time
     */
    public $rememberMeTime = 604800;

    /**
     * Return logout URL
     */
    public $returnLogoutUrl = array("/user/login");

    /**
     * Recovery URL
     */
    public $recoveryUrl = array("/user/recovery");

    /**
     * Login URL
     */
    public $loginUrl = array("/user/login");

    /**
     * Logout URL
     */
    public $logoutUrl = array("/user/logout");

    /**
     * Profile URL
     */
    public $profileUrl = array("/user/profile");

    /**
     * Profile URL
     */
    public $registrationUrl = array("/user/registration");

    /**
     * Hash method
     */
    public $encrypting = 'md5';

    /**
     * Hash function salt
     */
    public $salt = null;

    /**
     * Get model
     */
    private function _getModel()
    {
        if (!$this->isGuest && $this->_model === null)
            $this->_model = User::model()->with('profile')->findByPk($this->id);

        return $this->_model;
    }

    /**
     * Get hash result
     */
    public function encrypting($value)
    {
        $value = $this->salt.$value;

        switch ($this->encrypting){
            case "md5":
                return md5($value);
            break;
            case "sha1":
                return sha1($value);
            break;
        }
    }

    public function getLogin()
    {
        if ($user = $this->_getModel())
            return $user->login;
    }

    public function getRole()
    {
        if ($user = $this->_getModel())
            return $user->role;
    }

    public function getModel()
    {
        if ($user = $this->_getModel())
            return $this->_model;
    }

    public function getPassword()
    {
        if ($user = $this->_getModel())
            return $user->password;
    }

}