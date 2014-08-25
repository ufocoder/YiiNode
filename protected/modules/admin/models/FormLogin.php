<?php
/**
 * Форма входа
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormLogin extends CFormModel
{
    /**
     * Константы
     */
    const CAPTCHA_ATTEMPT = 3;
    const CAPTCHA_STATE = 'adminLoginFormCaptchaAttempt';

    /**
     * Фоля формы
     */
    public $login;
    public $password;
    public $rememberMe;
    public $verifyCode;

    /**
     * Инициализируем состояние пользователя
     */
    public function init()
    {
        if (!Yii::app()->user->hasState(self::CAPTCHA_STATE))
            Yii::app()->user->setState(self::CAPTCHA_STATE, 1);
    }

    /**
     * @return type Правила валидации атрибутов
     */
    public function rules()
    {
        $rules = array(
            array('login, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
            array('verifyCode', 'captchaAttempt')
        );

        if ($this->isCaptchaShowed())
            $rules[] = array('verifyCode', 'captcha', 'allowEmpty' => false);

        return $rules;
    }

    public function captchaAttempt($attribute, $params)
    {
        if ($this->hasErrors()){
            $count = Yii::app()->user->getState(self::CAPTCHA_STATE);
            Yii::app()->user->setState(self::CAPTCHA_STATE, $count + 1);
        }
    }

    public function isCaptchaShowed()
    {
        if (!Yii::app()->user->hasState(self::CAPTCHA_STATE))
            return true;

        $attempts = Yii::app()->user->getState(self::CAPTCHA_STATE);

        if ($attempts > self::CAPTCHA_ATTEMPT)
            return true;
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
                    $this->addError("username", Yii::t("site", "Email is incorrect."));
                break;
                case UserIdentity::ERROR_USERNAME_OR_PASSWORD_INVALID:
                    $this->addError("username", Yii::t("site", "Username is incorrect."));
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
