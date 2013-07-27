<?php
/**
 * User Identity
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    private $_login;

    /**
     * Error status list
     */
    const ERROR_NONE = 0;
    const ERROR_USERNAME_OR_PASSWORD_INVALID = 1;
    const ERROR_PASSWORD_INVALID = 2;
    const ERROR_EMAIL_OR_PASSWORD_INVALID = 3;
    const ERROR_STATUS_NOTACTIVE = 4;
    const ERROR_STATUS_BAN = 5;
    const ERROR_ROLES = 6;

    public function authenticate($allow_roles = array())
    {
        $params = array();

        // find user by e-mail or login
        if (strpos($this->username, "@")) {
            $params['email'] = $this->username;
        } else {
            $params['login'] = $this->username;
        }

        $user = User::model()->findByAttributes($params);

        // user is not exists
        if ($user === null)
        {
            if (strpos($this->username, "@"))
                $this->errorCode = self::ERROR_EMAIL_OR_PASSWORD_INVALID;
            else
                $this->errorCode = self::ERROR_USERNAME_OR_PASSWORD_INVALID;
        }
        // user is exists
        else
        {
            // role is not exists
            if (!empty($allow_roles) && !in_array($user->role, $allow_roles)){
                $this->_role = $user->role;
                $this->errorCode = self::ERROR_ROLES;
            }
            // user is not active
            else if ($user->status == 0)
            {
                $this->errorCode = self::ERROR_STATUS_NOTACTIVE;
            }
            // user is banned
            else if ($user->status == -1)
            {
                $this->errorCode = self::ERROR_STATUS_BAN;
            }
            // invalid password
            else if (Yii::app()->user->encrypting($this->password) !== $user->password)
            {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
            else
            {
                $this->errorCode = self::ERROR_NONE;

                // set attributes
                $this->_id = $user->id_user;
                $this->_login = $user->login;

                // update last visited time
                $user->time_visited = time();
                $user->save(false);
            }
        }

        return $this->errorCode;
    }

    /**
     * Get user id [used by CWebUser class]
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Get user name [used by CWebUser class]
     */
    public function getName()
    {
        return $this->_login;
    }

}