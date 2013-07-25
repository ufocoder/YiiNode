<?php
/**
 * Главный контроллер 'AdminModule'
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class DefaultController extends ControllerAdmin
{
    /**
     * Последние изменения [действие по умолчанию]
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}