<?php
/**
 * Default site settings
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormSettingDefault extends CFormModel
{
    public $sitename;
    public $emailAdmin;
    public $datetimeFormat;

    public static function values($setting = null, $value = null)
    {
        $settings = array(
            "datetimeFormat" => array(
                "list" => array(
                    'F j, Y g:i' => date('F j, Y g:i'),
                    'Y/m/d g:i:s' => date('Y/m/d g:i:s'),
                    'd.m.Y H:i' => date('d.m.Y H:i'),
                ),
                'default' => 'd.m.Y H:i'
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
            array('sitename', 'required'),
            array('emailAdmin', 'email'),
            array('datetimeFormat', 'default', 'value' => self::values('datetimeFormat', 'dafault'))
        );
    }

    /**
     * @return array Label list (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'sitename' => Yii::t('site', 'Site name'),
            'emailAdmin' => Yii::t('site', 'Email admin'),
            'datetimeFormat' => Yii::t('site', 'Datetime format'),
        );
    }
}
