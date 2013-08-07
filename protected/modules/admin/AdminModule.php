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

        // Bootstrap preload
        $this->configure(array(
            'components'=>array(
                'bootstrap'=>array(
                    'class'=>'ext.booster.components.Bootstrap'
                )
            )
        ));
        $this->getComponent('bootstrap');

        // assets
        $assets = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets');
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($assets . '/admin.js');

        // redefine user url list
        Yii::app()->user->recoveryUrl = array('/admin/recovery');
        Yii::app()->user->loginUrl = array('/admin/login');
        Yii::app()->user->returnUrl = array('/admin');
        Yii::app()->user->returnLogoutUrl = array('/admin/login');
    }

}
