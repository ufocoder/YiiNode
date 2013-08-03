<?php
/**
 *  PHP auth manager
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class phpAuthManager extends CPhpAuthManager
{
    public $defautRoles = array(WebUser::ROLE_GUEST);

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
        {
            $role = Yii::app()->user->getRole();
            $roles = array_keys($this->getAuthItems(CAuthItem::TYPE_ROLE));

            if (!empty($role) && in_array($role, $roles))
                $this->assign($role, Yii::app()->user->id);
        }


    }
}