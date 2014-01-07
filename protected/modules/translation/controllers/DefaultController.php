<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {

        Yii::app()->runController('contact/default/index');
        die();

        $path = explode(Yii::app()->getNode()->path, Yii::app()->request->url);

        $nodeId = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'nodeId');
        $node = Yii::app()->getNodeById($nodeId);

        $urlManager = Yii::app()->getModuleUrlManager($node);

        if (count($path) > 1)
        {
            $request = new CHttpRequest;

            $uri = $_SERVER['REQUEST_URI'];
            $_SERVER['REQUEST_URI'] = $node->path.$path[1];

            $request = new CHttpRequest;
            $route = $urlManager->parseUrl($request);
            Yii::app()->setComponent('request', $request);
            echo 'set';
            Yii::app()->runController($route);
            echo 'run';
        }


    }
}