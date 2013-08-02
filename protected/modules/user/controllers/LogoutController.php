<?php
/**
 * User module - Logout
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class LogoutController extends Controller
{
    /**
     * @return type Access rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index'),
                'users' => array('@'),
            ),
            array('deny')
        );
    }

    /**
     * Logout and redirect [index action]
     */
    public function actionIndex()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->user->returnLogoutUrl);
    }
}