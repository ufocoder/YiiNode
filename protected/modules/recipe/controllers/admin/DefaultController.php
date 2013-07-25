<?php

class DefaultController extends ControllerAdmin
{
    public $layout='//layouts/column2';

    private $_model;

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
    
        die();
        $model=new Post;
        if(isset($_POST['Post']))
        {
            $model->attributes=$_POST['Post'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('/recipe/create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        
        if(isset($_POST['Post']))
        {
            $model->attributes=$_POST['Post'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('recipe/update',array(
            'model'=>$model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=$this->loadModel();

        if(isset($_GET['tag']))
            $criteria->addSearchCondition('tags',$_GET['tag']);

        $dataProvider=new CActiveDataProvider('BlogPost', array(
            'pagination'=>array(
                'pageSize'=>Yii::app()->params['recipesPerPage'],
            ),
            'criteria'=>$criteria,
        ));

        $this->render('/recipe/index',array(
            'dataProvider'=>$dataProvider,
        ));

    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Post('search');
        if(isset($_GET['Post']))
            $model->attributes=$_GET['Post'];
        $this->render('/recipe/admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Suggests tags based on the current user input.
     * This is called via AJAX when the user is entering the tags input.
     */
    public function actionSuggestTags()
    {
        if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
        {
            $tags=BlogTag::model()->suggestTags($keyword);
            if($tags!==array())
                echo implode("\n",$tags);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel()
    {
        if($this->_model===null)
        {
            if(isset($_GET['id']))
            {
                if(Yii::app()->user->isGuest)
                    $condition='status='.BlogPost::STATUS_PUBLISHED.' OR status='.BlogPost::STATUS_ARCHIVED;
                else
                    $condition='';
                $this->_model=BlogPost::model()->findByPk($_GET['id'], $condition);
            }
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }

    /**
     * Creates a new comment.
     * This method attempts to create a new comment based on the user input.
     * If the comment is successfully created, the browser will be redirected
     * to show the created comment.
     * @param Post the recipe that the new comment belongs to
     * @return Comment the comment instance
     */
    protected function newComment($recipe)
    {
        $comment=new RecipeComment;
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
        {
            echo CActiveForm::validate($comment);
            Yii::app()->end();
        }
        if(isset($_POST['Comment']))
        {
            $comment->attributes=$_POST['Comment'];
            if($recipe->addComment($comment))
            {
                if($comment->status==Comment::STATUS_PENDING)
                    Yii::app()->user->setFlash('commentSubmitted','Thank you for your comment. Your comment will be recipeed once it is approved.');
                $this->refresh();
            }
        }
        return $comment;
    }
}
