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
    public $layout = '//layouts/column2';

    /**
     * Action array
     */
    public $actions = array();

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function createUrl($route,$params=array(),$ampersand='&')
    {
        if($route==='')
            $route=$this->getId().'/'.$this->getAction()->getId();
        elseif(strpos($route,'/')===false)
            $route=$this->getId().'/'.$route;
        if($route[0]!=='/' && ($module=$this->getModule())!==null)
            $route=$module->getId().'/'.$route;

        if ($nodeId = Yii::app()->getNodeId())
            $params['nodeId'] = $nodeId;

        return Yii::app()->createUrl(trim($route,'/'),$params,$ampersand);
    }

    // setup layout from node attributes
    public function beforeAction($action)
    {
        $node = Yii::app()->getNode();

        if (Yii::app()->Theme)
            $layouts = array_keys(Yii::app()->Theme->getSetting('layouts'));

        if (!empty($node) && isset($layouts) && in_array($node->layout, $layouts))
            $this->layout = "//layouts/".$node->layout;
        elseif (!empty($node) && $node->isRoot())
            $this->layout = "//layouts/default";

        return parent::beforeAction($action);
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