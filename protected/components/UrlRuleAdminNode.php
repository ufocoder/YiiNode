<?php
/**
 * Admin node rule
 *
 * @author Sergei Ivanov <xifrin@gmail.com>
 * @copyright 2013
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class UrlRuleAdminNode extends CBaseUrlRule
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
        if (empty($params['nodeId']) || empty($params['nodeAdmin']))
            return false;

        if (!empty($params['nodeId']))
            $node = Yii::app()->getNodeByID($params['nodeId']);
        else
            return false;

        unset($params['nodeId']);
        unset($params['nodeAdmin']);

        $_manager = Yii::app()->_getUrlManager();

        if (!empty($node))
            return 'admin/node/'.$node->id_node.$_manager->createUrl($route, $params, $ampersand);
        else
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
        preg_match('/^admin\/node\/(?<id>\d+)(\/(?<path>.*))?$/i', $pathInfo, $matches);
        if (!empty($matches['id'])){
            $node = Yii::app()->getNodeByID($matches['id']);
            if (empty($node->id_node)){
                if (YII_DEBUG)
                    throw new CException(Yii::t('yii','Node ID #{id_node} not exists.', array('{id_node}' => $matches['id'])));
                else
                    throw new CHttpException(404, Yii::t('site', 'The requested page does not exist.'));
            }

            Yii::app()->setNode($node, true);

            $path = !empty($matches['path'])?$matches['path']:'default';
            return $node->module."/admin/".$path;
        }
        return false;
    }

}