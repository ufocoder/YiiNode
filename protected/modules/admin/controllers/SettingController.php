<?php
/**
 * Admin module - Setting controller
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class SettingController extends ControllerAdmin
{
    /**
     * Index page with last changes widgets
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}