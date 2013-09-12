<?php
/**
 * Default site settings
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormSettingUser extends CFormModel
{
    /**
     * confirm constants
     */
    const CONFIRM_NONE = 1;
    const CONFIRM_MAIL = 2;

    public $userAllowRegister;
    public $userActiveAfterRegister;
    public $userConfirmTypeRegister;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "confirm" => array(
                'list' => array(
                    self::CONFIRM_NONE => Yii::t('site', 'User is active after registration'),
                    self::CONFIRM_MAIL => Yii::t('site', 'Send activation mail'),
                ),
                'default' => self::CONFIRM_NONE
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
        elseif (isset($settings[$setting]))
            return $settings[$setting];
    }

    /**
     * @return type rules
     */
    public function rules() {
        return array(
            array('userAllowRegister, userActiveAfterRegister', 'boolean', 'allowEmpty'=>true),
            array('userConfirmTypeRegister', 'in', 'range'=>array_keys(self::values('confirm', 'list')))
        );
    }

    /**
     * @return array Label list (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'userAllowRegister' => Yii::t('site', 'Allow registration'),
            'userActiveAfterRegister' => Yii::t('site', 'Active after registration'),
            'userConfirmTypeRegister' => Yii::t('site', 'Confirm type registration'),
        );
    }
}
