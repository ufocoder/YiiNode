<?php
/**
 * Admin module - Default controller
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class DefaultController extends ControllerAdmin
{
    /**
     * Index page with last changes widgets
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}