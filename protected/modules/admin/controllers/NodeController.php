<?php
/**
 * Главный контроллер 'AdminModule'
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class NodeController extends ControllerAdmin
{
    /**
     * Управление узлами
     */
    public function actionIndex()
    {
        $model=new Node('search');

        $model->unsetAttributes();  // Убрать значения по умолчанию
        if(isset($_GET['License']))
            $model->attributes=$_GET['License'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Создать новый узел
     */
    public function actionCreate()
    {
        $class = "Node";
        $model = new $class;
        $model->scenario = 'create';
        $model->isNewRecord = true;

        if (isset($_POST[$class]))
        {
            $model->attributes = $_POST[$class];
            if ($model->validate())
            {
                $model->moveNode();
                $model->saveNode();

                if (Yii::app()->request->isAjaxRequest){
                    echo CJSON::encode(array('success'=>true));
                    Yii::app()->end();
                }else{
                    Yii::app()->user->setFlash('success', Yii::t('site', 'Node was created success!'));
                    $this->redirect(array('index'));
                }
            }
        }

        $_modules = Yii::app()->modules;
        unset($_modules['admin']);
        $_modules = array_keys($_modules);

        $modules = array();
        foreach($_modules as $module)
            $modules[$module] = Yii::t('site', 'Module '.$module);

        $nodes = CHtml::listData(Node::model()->tree()->findAll(), 'id_node', 'title');

        $this->render('/node/create', array(
            'model' => $model,
            'nodes' => $nodes,
            'modules' => $modules
        ));
    }

    /**
     * Обновление текущий модели
     * Если обновление успешно, произойдет переадресация на страницу просмотра
     *
     * @param integer $id Идентификатор модели
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->scenario = 'update';

        $class = "Node";
        if (isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id_node));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Удаление текущей модели
     * Если удаление успешно, произойдет переадресация на страницу управления моделями
     *
     * @param integer $id Идентификатор модели
     */
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $model = $this->loadModel($id);
            $model->deleteNode();

            if (Yii::app()->request->isAjaxRequest)
            {
                echo CJSON::encode(array('success'=>true));
                Yii::app()->end();
            }
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');  
    }

    /**
     * Вернуть модель на основен первичного ключа полученного из GET переменной.
     * Если данные модели не найден, появляется HTTP исключение
     *
     * @param integer $id Идентификатор модели 
     * @return License загруженная модель
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Node::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested node does not exist.'));
        return $model;
    }

}