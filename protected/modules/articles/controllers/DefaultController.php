<?php

class DefaultController extends Controller
{
    /**
     * View articles
     */
    public function actionIndex()
    {
        $model = Article::model()->node()->findAll();

        $this->render('/index',array(
            'model'=>$model
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
     *
     * @param integer $id Идентификатор модели 
     * @return Station загруженная модель
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Article::model()->node()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}
