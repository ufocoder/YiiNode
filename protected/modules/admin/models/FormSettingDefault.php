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
                )
            )
        );

        if (isset($settings[$setting][$value]))
            return $settings[$setting][$value];
    }

    /**
     * @return type rules
     */
    public function rules() {
        return array(
            array('sitename', 'required'),
            array('emailAdmin', 'email'),
            array('datetimeFormat', 'type', 'type' => 'datetime')
        );
    }

    /**
     * @return array Ìåòêè àòðèáóòîâ (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'sitename' => Yii::t('site', 'Site name'),
        );
    }
}
