<?php
/**
 * Admin module - Default controller
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class DefaultController extends ControllerAdmin
{

    public $layout = 'application.modules.admin.views.layouts.column1';

    /**
     * Index page with last changes widgets
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}