<?php
/**
 * Admin module - Menu / List
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class ListController extends ControllerAdmin
{
    /**
     * Main layout
     */
    public $layout = 'application.modules.admin.views.layouts.column1';

    /**
     * Просмотреть текущий блок
     *
     * @param integer $id Идентификатор модели
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->render('/menu/list/view', array(
            'model'=>$model,
        ));
    }

    /**
     * Создание модели модели
     *
     * @param integer $id Идентификатор модели
     */
    public function actionCreate()
    {
        $class = "MenuList";
        $model = new $class;
        $model->scenario = 'create';

        if (isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('/menu/list/create',array(
            'model'=>$model,
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
        $class = "MenuList";
        $model=$this->loadModel($id);
        $model->scenario = 'update';

        if (isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('/menu/list/update',array(
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
        $this->loadModel($id)->delete();

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Управление моделями
     */
    public function actionIndex()
    {
        $class = 'MenuList';
        $model = new $class('search');
        $model->unsetAttributes();
        if(isset($_GET[$class]))
            $model->attributes=$_GET[$class];

        $this->render('/menu/list/index',array(
            'model'=>$model,
        ));
    }
    /**
     * Вернуть модель на основен первичного ключа полученного из GET переменной.
     * Если данные модели не найден, появляется HTTP исключение
     *
     * @param integer $id Идентификатор модели
     * @return MenuList загруженная модель
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = MenuList::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }
}