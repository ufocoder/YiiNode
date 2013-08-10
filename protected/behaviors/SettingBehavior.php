<?php
/**
 * Setting behavior
 *
 * @author Sergei Ivanov <xifrin@gmail.com>
 * @copyright 2013
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class SettingBehavior extends CBehavior
{
    protected $_setting_root;
    protected $_setting_node;

    /**
     * Declares events and the event handler methods
     */
    public function events()
    {
        return array_merge(parent::events(), array(
            'onBeginRequest' => 'beginRequest',
        ));
    }

    /**
     * Load configuration that cannot be put in config/main
     */
    public function beginRequest()
    {
        $settings = Setting::model()->findAll();
        foreach($settings as $setting){
            $item = array(
                'id_setting' => $setting->id_setting,
                'value' => $setting->value,
                'time_created' => $setting->time_created,
                'time_updated' => $setting->time_updated
            );
            if (!empty($setting->id_node))
                $this->_setting_node[$setting->id_node][$setting->title] = $item;
            else
                $this->_setting_root[$setting->title] = $item;
        }
    }

    public function getSetting($title, $default = null)
    {
        if (isset($this->_setting_root[$title]))
            return $this->_setting_root[$title]['value'];
        else
            return $default;
    }

    public function setSetting($title, $value = null)
    {
        if (empty($title) || empty($value))
            return false;

        if (isset($this->_setting_root[$title]))
        {
            $item = $this->_setting_root[$title];
            $setting = Setting::model()->findByPk($item['id_setting']);
            $setting->value = $value;
            $setting->time_updated = time();
            return $setting->save();
        }
        else
        {
            $setting = new Setting;
            $setting->title = $title;
            $setting->value = $value;
            $setting->time_created = time();
            $setting->time_updated = null;
            return $setting->save();
        }
    }

    public function setSettings($attributes = array())
    {
        if (!empty($attributes))
            foreach($attributes as $title => $value)
                $this->setSetting($title, $value);
    }

    public function getNodeSetting($id_node, $title, $default = null)
    {
        if (isset($this->_setting_node[$id_node][$title]))
            return $this->_setting_node[$id_node][$title]['value'];
        else
            return $default;
    }

    public function setNodeSetting($id_node, $title, $value)
    {
        if (empty($id_node) || empty($title) || empty($value))
            return false;

        if (isset($this->_setting_node[$id_node][$title]))
        {
            $item = $this->_setting_node[$id_node][$title];
            $setting = Setting::model()->findByPk($item['id_setting']);
            $setting->value = $value;
            $setting->time_updated = time();
            return $setting->save();
        }
        else
        {
            $setting = new Setting;
            $setting->id_node = $id_node;
            $setting->title = $title;
            $setting->value = $value;
            $setting->time_created = time();
            $setting->time_updated = null;
            return $setting->save();
        }
    }


    public function setNodeSettings($id_node, $attributes = array())
    {
        if (!empty($id_node) && !empty($attributes))
            foreach($attributes as $title => $value)
                $this->setNodeSetting($id_node, $title, $value);
    }

}