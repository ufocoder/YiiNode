<?php
/**
 * Admin module - Blocks
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class BlockController extends ControllerAdmin
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
        $this->render('/block/view', array(
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
        $class = "Block";
        $model=$this->loadModel($id);
        $model->scenario = 'update';

        if (isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            $types = array($model::TYPE_FILE, $model::TYPE_IMAGE, $model::TYPE_FLASH);
            if (in_array($model->type, $types)){
                $instance = CUploadedFile::getInstance($model, 'content');
                if (!empty($instance)){
                    $extension = CFileHelper::getExtension($instance->getName());
                    $pathname = Block::getUploadPath();
                    $filename = md5(time()) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->content = $baseUrl . $model::getUploadPath() .$filename;
                }
            }
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('/block/update',array(
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
        $class = 'Block';
        $model = new $class('search');
        $model->unsetAttributes();
        if(isset($_GET[$class]))
            $model->attributes=$_GET[$class];

        $this->render('/block/index',array(
            'model'=>$model,
        ));
    }
    /**
     * Вернуть модель на основен первичного ключа полученного из GET переменной.
     * Если данные модели не найден, появляется HTTP исключение
     *
     * @param integer $id Идентификатор модели
     * @return Block загруженная модель
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Block::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }
}