<?php

class DefaultController extends Controller
{
    public $layout = "//layouts/recipe";

    /**
     * View articles
     */
    public function actionIndex()
    {
        $recipes = Recipe::model()->findAll();

        $this->render('/index', array(
            'recipes' => $recipes
        ));
    }

    /**
     * Просмотр рецепта
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
        $model = Recipe::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}