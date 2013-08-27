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
        // assets
        $path = Yii::getpathOfAlias('application.modules.admin.assets');
        $assets = Yii::app()->assetManager->publish($path);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($assets . '/admin.js');
        $cs->registerScriptFile($assets . '/jquery.synctranslit.min.js');

        // Bootstrap preload
        Yii::app()->getComponent("bootstrap");

        return true;
    }


    public function createUrl($route, $params=array(), $ampersand='&')
    {
        $flag_admin = !empty($matches[1]);
        $flag_module = !in_array($this->module->id, array('admin','user'));

        if ($flag_admin && $flag_module){

            $controller = $matches[1];

            if($route==='')
                $route=$controller.'/'.$this->getAction()->getId();
            elseif(strpos($route,'/')===false)
                $route=$controller.'/'.$route;

            if ($nodeId = Yii::app()->getNodeId())
                $params['nodeId'] = $nodeId;

            $params['nodeAdmin'] = true;

            return Yii::app()->createUrl(trim($route,'/'),$params,$ampersand);
        }
        else
        {
            if($route==='')
                $route=$this->getId().'/'.$this->getAction()->getId();
            elseif(strpos($route,'/')===false)
                $route=$this->getId().'/'.$route;
            if($route[0]!=='/' && ($module=$this->getModule())!==null)
                $route=$module->getId().'/'.$route;

             return Yii::app()->createUrl(trim($route,'/'),$params,$ampersand);
        }

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