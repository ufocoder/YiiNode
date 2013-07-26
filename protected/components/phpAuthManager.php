<?php
/**
 *  PHP auth manager
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class phpAuthManager extends CPhpAuthManager
{
    /**
     * Auth manager initialize
     */ 
    public function init()
    {
        if ($this->authFile===null)
            $this->authFile=Yii::getPathOfAlias('application.config.auth').'.php';
 
        parent::init();

        // assign role
        if (!Yii::app()->user->isGuest)
            $this->assign(Yii::app()->user->getRole(), Yii::app()->user->id);
    }
}