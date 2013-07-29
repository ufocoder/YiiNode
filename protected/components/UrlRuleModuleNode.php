<?php
/**
 * Module node rule
 *
 * @author Sergei Ivanov <xifrin@gmail.com>
 * @copyright 2013
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class UrlRuleModuleNode extends CBaseUrlRule
{
    /**
     * Create Url
     *
     * @param type $manager
     * @param type $route
     * @param type $params
     * @param type $ampersand
     * @return boolean
     */
    public function createUrl($manager,$route,$params,$ampersand)
    {
        if (empty($params['nodeId']) && empty($params['nodePath']))
            return false;

        if (!empty($params['nodeId']))
            $node = Yii::app()->getNodeByID($params['nodeId']);
        elseif (!empty($params['nodePath']))
            $node = Yii::app()->getNodeByPath($params['nodePath']);
        else
            return false;

        if ($manager = Yii::app()->getModuleUrlManager($node)){
            return ltrim($manager->createUrl($route, $params, $ampersand), "/");
        }else
            return false;
    }

    /**
     * Parse URL
     *
     * @param type $manager
     * @param type $request
     * @param type $pathInfo
     * @param type $rawPathInfo
     * @return boolean
     * @throws CException
     * @throws CHttpException
     */
    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        $path = Yii::app()->getRequest()->getPathInfo();

        $criteria = new CDbCriteria;
        $criteria->order = 'path';
        $criteria->addInCondition('path', Yii::app()->_getNodePathList($path));

        $nodes = Node::model()->active()->findAll($criteria);
        $node = end($nodes);

        Yii::app()->setNode($node);

        $chain = array();
        foreach($nodes as $node)
            $chain[$node->id_node] = $node;
        Yii::app()->setNodeChain($chain);
        
        if (!empty($node) && $node->isRoot())
            Yii::app()->controller->layout = "//layouts/default";

        if ($urlManager = Yii::app()->getModuleUrlManager($node))
            return $urlManager->parseUrl($request);
    }

}