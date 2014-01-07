<?php
/**
 * Default site settings
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class SettingDefault extends CFormModel
{
    public $sitename;
    public $emailAdmin;
    public $datetime;

    public static function values($setting = null, $item = null, $value = null)
    {
        $settings = array(
            "datetime" => array(
                "list" => array(
                    'F j, Y g:i' => date('F j, Y g:i'),
                    'Y/m/d g:i:s' => date('Y/m/d g:i:s'),
                    'd.m.Y H:i' => date('d.m.Y H:i'),
                    'd.m.Y' => date('d.m.Y'),
                ),
                'default' => 'd.m.Y H:i'
            ),
            'datetimeFormat' => array(
                'list' => array(
                    'F j, Y g:i' => date('F j, Y g:i'),
                    'Y/m/d g:i:s' => date('Y/m/d g:i:s'),
                    'd.m.Y H:i' => "dd.MM.yyyy hh:mm",
                    'd.m.Y' => "dd.MM.yyyy",
                ),
                'default' => "yyyy-MM-dd hh:mm"
            )
        );


        if (!empty($value) && !empty($settings[$setting][$item][$value]))
            return $settings[$setting][$item][$value];
        elseif (!empty($item) && !empty($settings[$setting][$item]))
            return $settings[$setting][$item];
        elseif (!empty($settings[$setting]))
            return $settings[$setting];
    }

    /**
     * @return type rules
     */
    public function rules() {
        return array(
            array('sitename', 'required'),
            array('emailAdmin', 'email'),
            array('datetime', 'default', 'value' => self::values('datetime', 'dafault'))
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
            'datetime' => Yii::t('site', 'Datetime format'),
        );
    }
}
