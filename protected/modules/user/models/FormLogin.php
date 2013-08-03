<?php
/**
 * Form login
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormLogin extends CFormModel
{
    public $login;
    public $password;
    public $rememberMe;
    public $verifyCode;

    /**
     * @return type Правила валидации атрибутов
     */
    public function rules() {
        return array(
            array('login, password', 'required'),
            array('rememberMe', 'boolean'),
            array('verifyCode', 'captcha', 'allowEmpty'=>false),
            array('password', 'authenticate'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'login' => Yii::t('site', 'Username or email'),
            'password' => Yii::t('site', 'Password'),
            'rememberMe' => Yii::t('site', 'Remember me next time'),
        );
    }

    /**
     * Аутентификация пользователя по паролю [правило валидации]
     *
     * @param type $attribute
     * @param type $params
     */
    public function authenticate($attribute, $params)
    {
        // @TODO: user module options

        if (!$this->hasErrors())
        {
            $identity = new UserIdentity($this->login, $this->password);
            $identity->authenticate();

            switch ($identity->errorCode)
            {
                case UserIdentity::ERROR_NONE:
                    $duration = $this->rememberMe ? Yii::app()->user->rememberMeTime : 0;
                    Yii::app()->user->login($identity, $duration);
                break;
                case UserIdentity::ERROR_EMAIL_OR_PASSWORD_INVALID:
                    $this->addError("username", Yii::t("site", "Email or password is incorrect."));
                break;
                case UserIdentity::ERROR_USERNAME_OR_PASSWORD_INVALID:
                    $this->addError("username", Yii::t("site", "Username or password is incorrect."));
                break;
                case UserIdentity::ERROR_STATUS_NOTACTIVE:
                    $this->addError("status", Yii::t("site", "You account is not activated."));
                break;
                case UserIdentity::ERROR_STATUS_BAN:
                    $this->addError("status", Yii::t("site", "You account is blocked."));
                break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError("password", Yii::t("site", "Password is incorrect."));
                break;
            }
        }
    }
}
