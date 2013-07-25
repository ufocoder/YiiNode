<?php
/**
 * Форма изменения пароля
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormChangePassword extends CFormModel
{
    public $oldPassword;
    public $password;
    public $verifyPassword;

    /**
     * @return type Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('password, verifyPassword', 'required', 'on' => 'recovery'),
            array('password, verifyPassword', 'length', 'max' => 128, 'min' => 4, 'message' => Yii::t("site", "Incorrect password (minimal length 4 symbols).")),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t("site", "Retype Password is incorrect."), 'on' => 'recovery'),
            array('oldPassword, password, verifyPassword', 'required', 'on' => 'change'),
            array('oldPassword, password, verifyPassword', 'length', 'max' => 128, 'min' => 4, 'message' => Yii::t("site", "Incorrect password (minimal length 4 symbols)."), 'on' => 'change'),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t("site", "Retype Password is incorrect."), 'on' => 'change'),
            //
            array('oldPassword', 'verifyOldPassword', 'on'=>'change'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'oldPassword' => Yii::t("site", "Old Password"),
            'password' => Yii::t("site", "Password"),
            'verifyPassword' => Yii::t("site", "Retype Password"),
        );
    }

    /**
     * Проверка пароля [правило валидации]
     *
     * @param type $attribute
     * @param type $params
     */
    public function verifyOldPassword($attribute, $params)
    {
        if (User::model()->findByPk(Yii::app()->user->id)->password != Yii::app()->user->encrypting($this->$attribute))
        {
            $this->addError($attribute, Yii::t("site", "Old Password is incorrect."));
        }
    }

}