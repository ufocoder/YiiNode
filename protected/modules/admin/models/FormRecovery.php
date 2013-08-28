<?php
/**
 * Форма восстановления пароля
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormRecovery extends CFormModel
{
    public $login_or_email;
    public $id_user;
    public $verifyCode;

    /**
     * @return type Правила валидации атрибутов
     */
    public function rules()
    {
        return array(
            array('login_or_email', 'required'),
            array('login_or_email', 'match', 'pattern' => '/^[A-Za-z0-9@.-\s,]+$/u','message' => Yii::t("site", "Incorrect symbols (A-z0-9).")),
            array('verifyCode', 'captcha', 'allowEmpty'=>false),
            array('login_or_email', 'userExists'),
        );
    }

    /**
     * @return array Метки атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'login' => Yii::t("site", "login or email"),
        );
    }

    /**
     * Проверка существования пользователя [правило валидации]
     *
     * @param type $attribute аттрибуту
     * @param type $params параметры
     */
    public function userExists($attribute, $params)
    {
        // проверяем существования пользователя, только если нет ошибок
        if(!$this->hasErrors())
        {
            if (strpos($this->login_or_email,"@"))
            {
                $user=User::model()->findByAttributes(array(
                    'email'=>$this->login_or_email
                ));
                if ($user){
                    $this->id_user=$user->id_user;
                }
            }
            else
            {
                $user=User::model()->findByAttributes(array(
                    'login'=>$this->login_or_email
                ));
                if ($user)
                {
                    $this->id_user=$user->id_user;
                }
            }

            if($user === null)
            {
                if (strpos($this->login_or_email, "@"))
                {
                    $this->addError("login_or_email", Yii::t("error", "Email is incorrect."));
                }
                else
                {
                    $this->addError("login_or_email", Yii::t("error", "Username is incorrect."));
                }
            }
        }
    }

}