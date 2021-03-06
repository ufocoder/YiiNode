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
                $flag = $model->saveAsNode();

                if (Yii::app()->request->isAjaxRequest){
                    echo CJSON::encode(array('success' => $flag));
                    Yii::app()->end();
                }else{
                    if ($flag)
                        Yii::app()->user->setFlash('success', Yii::t('site', 'Node was created success!'));
                    else
                        Yii::app()->user->setFlash('warning', Yii::t('error', 'There was error on creating node!'));
                    $this->redirect(array('index'));
                }
            }
        }

        $list = Yii::app()->getNodeModules();

        $modules = array();
        foreach($list as $module)
            $modules[$module] = Yii::t('module', $module);

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

        Yii::app()->setNode($model);

        $class = "Node";
        if (isset($_POST[$class]))
        {
            $model->attributes = $_POST[$class];
            if ($model->saveNode()){
                $model->updatePaths();
                $this->redirect(array('index'));
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Обновление текущий модели
     * Если обновление успешно, произойдет переадресация на страницу просмотра
     *
     * @param integer $id Идентификатор модели
     */
    public function actionMove($id)
    {
        $model = $this->loadModel($id);
        $model->scenario = 'move';

        Yii::app()->setNode($model);

        if ($model->isRoot()){
            Yii::app()->user->setFlash('warning', Yii::t('error', 'Root node couldn\'t be moved.'));
            $this->redirect(array('index'));
        }

        $class = "Node";
        if (isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if ($model->saveAsNode())
                $this->redirect(array('index'));
        }

        $nodes = CHtml::listData(Node::model()->tree()->findAll(), 'id_node', 'title');

        $this->render('move',array(
            'model'=>$model,
            'nodes'=>$nodes,
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
        $model = $this->loadModel($id);
        $model->deleteNode();

        if (Yii::app()->request->isAjaxRequest)
        {
            echo CJSON::encode(array('success'=>true));
            Yii::app()->end();
        }
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Вернуть модель на основен первичного ключа полученного из GET переменной.
     * Если данные модели не найден, появляется HTTP исключение
     *
     * @param integer $id Идентификатор модели
     * @return Node загруженная модель
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