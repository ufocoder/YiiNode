<?php
/**
 * Admin module [with Bootstrap]
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class AdminModule extends WebModule
{
    /**
     * Flag module is node type
     */
    public $nodeModule = false;

    /**
     * Module initialization
     */
    public function init()
    {
        parent::init();

        // Import module models and components
        $this->setImport(array(
            'admin.components.*',
            'admin.models.*',
        ));

        // redefine user url list
        Yii::app()->user->recoveryUrl = array('/admin/recovery');
        Yii::app()->user->loginUrl = array('/admin/login');
        Yii::app()->user->returnUrl = array('/admin');
        Yii::app()->user->returnLogoutUrl = array('/admin/login');
    }

    /**
     * Returns the description of this module.
     *
     * @return string the description of this module.
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Returns the version of this module.
     *
     * @return string the version of this module.
     */
    public function getVersion()
    {
        return '1.0';
    }

}