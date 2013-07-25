<?php
/**
 * Форма регистрации пользователя и компании 
 * с одновременным приоритением лицензии
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormRegister extends CFormModel
{
    public $login;
    public $email;

    public $person_name;

    public $password;
    public $verifyPassword;
    public $verifyCode;

    /**
     * @return type Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('login, domen, email, person_name, password, verifyPassword, company_title', 'required'),
            array('login', 'length', 'max'=>20, 'min' => 3, 'message' => Yii::t("site", "Incorrect login (length between 3 and 20 characters).")),
            array('password', 'length', 'max'=>128, 'min' => 4, 'message' => Yii::t("site", "Incorrect password (minimal length 4 symbols).")),
            array('email', 'email'),
            array('login', 'unique', 'className'=>'User', 'message' => Yii::t("site", "This user's name already exists.")),
            array('email', 'unique', 'className'=>'User', 'message' => Yii::t("site", "This user's email already exists.")),
            array('company_title', 'unique', 'className'=>'Company', 'attributeName'=>'title', 'message' => Yii::t("site", "This user's name already exists.")),
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
            'password' => Yii::t('site', 'Password'),
            'rememberMe' => Yii::t('site', 'Remember me next time'),
        );
    }

}
