<?php

class CategoryController extends Controller
{
    public $layout = "//layouts/recipe";

    /**
     * View articles
     */
    public function actionIndex($id = null)
    {
        $recipes = Recipe::model()->findAll();

        $this->render('/index',array(
            'recipes' => $recipes
        ));
    }

    /**
     * Просмотр рецепта
     */
    public function actionView($id = null)
    {
        $model=$this->loadModel($id);

        $recipes = Recipe::model()->findAll();

        $this->render('/index',array(
            'recipes' => $recipes
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
        $model = RecipeCategory::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

}
