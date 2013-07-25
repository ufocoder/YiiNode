<?php

class SearchController extends Controller
{
    public $layout = "//layouts/recipe";

    /**
     * Поиск рецепта по названию
     */
    public function actionIndex()
    {
        $recipes = null;

        $term = Yii::app()->getRequest()->getParam('q', null);
        $query = null;
        $pages = null;
        $count = null;

        if (!empty($term) && strlen($term) > 4)
        {
            $settings   = new RecipeSetting;
            $size       = Setting::getItem('pager', 'search', $settings->values('pager', 'default')); 
            
            $criteria = new CDbCriteria();
            $criteria->condition = 't.enabled = 1';
            $criteria->addSearchCondition('t.title', $term);

            $count = Recipe::model()->count($criteria);

            $criteria->limit = $size;
            $recipes  = Recipe::model()->findAll($criteria);

            $pages = new CPagination($count);
            $pages->pageSize = $size;
            $pages->applyLimit($criteria);
        }

        
        $this->render('/search', array(
            'recipes' => $recipes,
            'term' => $term,
            'pages' => $pages,
            'count' => $count
        ));
    }

}