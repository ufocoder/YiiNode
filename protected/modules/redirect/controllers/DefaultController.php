<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $nodeId = Yii::app()->getNodeId();

        $nodeRedirectId = Yii::app()->getNodeSetting($nodeId, 'nodeId');
        $url = Yii::app()->getNodeSetting($nodeId, 'url');

        $node = Yii::app()->getNodeByID($nodeRedirectId);

        if (!empty($url))
            $this->redirect($url);
        elseif(!empty($node))
            $this->redirect($node['path']);
    }
}