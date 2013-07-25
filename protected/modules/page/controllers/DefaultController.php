<?php

class DefaultController extends Controller
{
    /**
     * View page
     */
    public function actionIndex()
    {
        $model=$this->loadModel();

        $this->render('/index',array(
            'model'=>$model
        ));
    }

    /**
     * �������� ������
     *
     * @param integer $id ������������� ������ 
     * @return Station ����������� ������
     * @throws CHttpException
     */
    public function loadModel()
    {
        $nodeId = Yii::app()->getNodeId();
        $model = Page::model()->findByPk($nodeId);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}
