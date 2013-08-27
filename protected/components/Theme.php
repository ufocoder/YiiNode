<?php

class Theme extends CTheme
{
    protected $_setting = array();

    public function __construct($name, $basePath, $baseUrl)
    {
        parent::__construct($name, $basePath, $baseUrl);

        $path = Yii::app()->themeManager->getBasePath() .DIRECTORY_SEPARATOR .$this->getName();
        $file = $path . DIRECTORY_SEPARATOR . 'settings.php';
        if (file_exists($file))
            $this->_setting = require_once $file;
    }

    public function getSetting($setting, $default = null)
    {
        if (isset($this->_setting[$setting]))
            return $this->_setting[$setting];
        else
            return $default;
    }

}