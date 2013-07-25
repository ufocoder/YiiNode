<?php
/**
 * ������� ���������� 'AdminModule'
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class LogoutController extends ControllerAuth
{
    /**
     * ������ ������ �������
     *
     * @return type
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

    
    public function actionIndex()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->user->returnLogoutUrl);
    }
}