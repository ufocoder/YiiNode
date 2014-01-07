<?php

class UserController extends ControllerAdmin
{
    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionIndex() {

        $criteria = new CDbCriteria;
        $class_model = "OrderItem";
        $model = new $class_model('search');

        $model->unsetAttributes();

        if (isset($_POST[$class_model]))
            $model->attributes = $_POST[$class_model];

        $this->render('/admin/user/admin', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id_user = 0) {

        $id_user = intval($id_user);
        $class_model = "OrderDiscount";

        $model = OrderDiscount::model()->findByPk($id_user);

        if (empty($model))
            $model = new OrderDiscount;

        $model->id_user = $id_user;

        if (isset($_POST[$class_model])) {
            $model->attributes = $_POST[$class_model];

            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('site', 'Form values were saved!'));
                $this->redirect(array('/order/user/index'));
            }
        }

        $this->render('/admin/user/update', array(
            'model' => $model,
        ));
    }

}
