<?php
/**
 * User module - Registration Form
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormRegistration extends CFormModel
{
    public $login;
    public $email;
    public $password;
    public $verifyPassword;
    public $verifyCode;

    /**
     * @return type Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('login, email, password, verifyPassword', 'required'),
            array('login', 'length', 'max'=>20, 'min' => 3, 'message' => Yii::t("site", "Incorrect login (length between 3 and 20 characters).")),
            array('password', 'length', 'max'=>128, 'min' => 4, 'message' => Yii::t("site", "Incorrect password (minimal length 4 symbols).")),
            array('email', 'email'),
            array('login', 'unique', 'className'=>'User', 'message' => Yii::t("site", "This user's name already exists.")),
            array('email', 'unique', 'className'=>'User', 'message' => Yii::t("site", "This user's email already exists.")),
            array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => Yii::t("site", "Retype password is incorrect.")),
            array('login', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => Yii::t("site", "Incorrect symbols (A-z0-9).")),
            array('verifyCode', 'captcha', 'allowEmpty'=>false),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'login' => Yii::t('site', 'Login'),
            'email' => Yii::t('site', 'Email'),
            'password' => Yii::t('site', 'Password'),
            'verifyPassword' => Yii::t('site', 'Verify password'),
            'verifyCode' => Yii::t('site', 'Verify code'),
        );
    }

}
