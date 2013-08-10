<?php

class DefaultController extends Controller
{
    /**
     * View articles
     */
    public function actionIndex()
    {
        $default = ArticleSetting::values('pager', 'default');
        $pager = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'pager', $default);

        $model = Article::model()->node()->published();

        $dataProvider = new CActiveDataProvider($model, array(
            'pagination' => array(
                'pageSize' => 1,
                'pageVar'  => 'page'
            )
        ));

        $this->render('/index', array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * View article
     */
    public function actionView($id)
    {
        $model=$this->loadModel($id);

        $this->render('/view',array(
            'model'=>$model
        ));
    }

    /*
     * Load model by id
     *
     * @param integer $id Идентификатор модели
     * @return Article model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Article::model()->node()->published()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}
