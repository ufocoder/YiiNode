<?php
/**
 * Base controller
 *
 * @author Sergei Ivanov <xifrin@gmail.com>
 * @copyright 2013
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class Controller extends CController
{
    /**
     * Page title, html tag 'title'
     */
    public $pageTitle;

    /**
     * Page title, html tag 'h1'
     */
    public $title;

    /**
     * Menu item list
     */
    public $menu;

    /**
     * Breadcrumbs
     */
    public $breadcrumbs = array();

    /**
     * Layout
     */
    public $layout = '//layouts/default';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Error action with own layout
     */
    public function actionError()
    {
        $this->layout='//layouts/error';
        if ( $error = Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
        else
            Yii::app()->request->redirect('/');
    }

}