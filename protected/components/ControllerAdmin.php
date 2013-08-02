<?php
/**
 * Admin controller
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class ControllerAdmin extends Controller
{
    /**
     * layout
     */
    public $layout = 'application.modules.admin.views.layouts.column2';

    /**
     * Title button
     */
    public $titleButton;

    /**
     * Title menu
     */
    public $titleMenu;

    /**
     * Action list
     */
    public $actions;

    /**
     * Tabs
     */
    public $tabs;

    /**
     * Access control
     *
     * @return type
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Access control rules
     *
     * @return type
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array(WebUser::ROLE_MODERATOR, WebUser::ROLE_ADMIN)
            ),
            array('deny')
        );
    }

    // @TODO: setup layouts in node attributes
    public function beforeAction($action)
    {
        return true;
    }

    /**
     * Render [переопредление]
     *
     * @param type $view файл представления
     * @param type $data данные шаблона
     * @param type $return флаг вывода
     */
    public function render($view, $data=null, $return=false)
    {
        if (Yii::app()->request->isAjaxRequest === true)
            parent::renderPartial($view, $data, $return, false);
        else
            parent::render($view, $data, $return);
    }

}