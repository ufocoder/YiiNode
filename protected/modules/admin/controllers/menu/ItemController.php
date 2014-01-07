<?php
/**
 * Admin module - Menu / Item
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class ItemController extends ControllerAdmin
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
    public function actionView($id_menu_list, $id)
    {
        $menu = $this->loadMenu($id_menu_list);
        $model = $this->loadModel($id, $id_menu_list);
        $this->render('/menu/item/view', array(
            'model' => $model,
            'menu' => $menu
        ));
    }


    public function actionCreate($id_menu_list)
    {
        $menu = $this->loadMenu($id_menu_list);

        $class = "MenuItem";
        $model = new $class;

        if (isset($_POST[$class]))
        {
            $model->attributes = $_POST[$class];
            $model->id_menu_list = $id_menu_list;

            if ($model->save()){
                // upload icon
                $instance = CUploadedFile::getInstance($model, 'x_icon');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = $class::getUploadPath();
                    $filename   = md5(time().Yii::app()->getNodeId()) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('icon' => $baseUrl . $model::getUploadPath() .$filename));
                }

                // upload image
                $instance = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = $class::getUploadPath();
                    $filename   = md5(time().Yii::app()->getNodeId()) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() .$filename));
                }
                Yii::app()->user->setFlash('success', Yii::t('site', 'Gallery image was created successful!'));
                $this->redirect(array('/admin/menu/item/index', 'id_menu_list'=>$menu->id_menu_list));
            }
        }

        $this->layout = "application.modules.admin.views.layouts.column1";

        $this->render('/menu/item/create',array(
            'model' => $model,
            'menu' => $menu
        ));
    }


    /**
     * Обновление текущий модели
     * Если обновление успешно, произойдет переадресация на страницу просмотра
     *
     * @param integer $id Идентификатор модели
     */
    public function actionUpdate($id_menu_list, $id)
    {
        $menu = $this->loadMenu($id_menu_list);

        $class = "MenuItem";

        $model = $this->loadModel($id, $id_menu_list);

        if (isset($_POST[$class]))
        {
            $model->attributes = $_POST[$class];
            // delete icon
            if ($model->delete_icon){
                $filename = $class::getUploadPath().$model->icon;
                if (file_exists($filename) && !empty($model->icon))
                    unlink($filename);
                $model->saveAttributes(array('icon'=>null));
            }

            // delete image
            if ($model->delete_image){
                $filename = $class::getUploadPath().$model->image;
                if (file_exists($filename) && !empty($model->image))
                    unlink($filename);
                $model->saveAttributes(array('image'=>null));
            }

            if ($model->save()){
                // upload icon
                $instance   = CUploadedFile::getInstance($model, 'x_icon');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = $class::getUploadPath();
                    $filename   = md5(time().Yii::app()->getNodeId()) . '.' . $extension;
                    $baseUrl = Yii::app()->request->getBaseUrl();
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('icon' => $baseUrl . $model::getUploadPath() .$filename));
                }
                // upload image
                $instance   = CUploadedFile::getInstance($model, 'x_image');
                if (!empty($instance)){
                    $extension  = CFileHelper::getExtension($instance->getName());
                    $pathname   = $class::getUploadPath();
                    $filename   = md5(time().Yii::app()->getNodeId()) . '.' . $extension;
                    if (empty($baseUrl))
                        $baseUrl = "/";
                    if ($instance->saveAs($pathname.$filename))
                        $model->saveAttributes(array('image' => $baseUrl . $model::getUploadPath() .$filename));
                }
                $this->redirect(array('/admin/menu/item/view', 'id_menu_list'=>$menu->id_menu_list, 'id'=>$model->id_menu_item));
            }
        }

        $this->render('/menu/item/update',array(
            'model'=>$model,
            'menu' => $menu
        ));
    }

    /**
     * Удаление текущей модели
     * Если удаление успешно, произойдет переадресация на страницу управления моделями
     *
     * @param integer $id Идентификатор модели
     */
    public function actionDelete($id_menu_list, $id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Управление моделями
     */
    public function actionIndex($id_menu_list)
    {
        $menu = $this->loadMenu($id_menu_list);

        $class = 'MenuItem';
        $model = new $class;
        $model->search($id_menu_list);
        $model->unsetAttributes();
        if(isset($_GET[$class]))
            $model->attributes=$_GET[$class];

        $this->render('/menu/item/index',array(
            'model' => $model,
            'menu' => $menu,
        ));
    }
    /**
     * Вернуть модель на основен первичного ключа полученного из GET переменной.
     * Если данные модели не найден, появляется HTTP исключение
     *
     * @param integer $id Идентификатор модели
     * @return MenuItem загруженная модель
     * @throws CHttpException
     */
    public function loadModel($id, $id_menu_list = null)
    {
        $model = MenuItem::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }


    public function loadMenu($id)
    {
        $model = MenuList::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }

}