<?php

class DefaultController extends Controller
{
    /**
     * View page
     */
    public function actionIndex()
    {

        $nodeId = Yii::app()->getNodeId();
        $model = Page::model()->findByPk($nodeId);
        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));

        $this->render('/index',array(
            'model'=>$model
        ));
    }

}
