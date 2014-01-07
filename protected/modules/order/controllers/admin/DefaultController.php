<?php

class DefaultController extends ControllerAdmin
{
    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionIndex() {

        $class_model = "OrderItem";
        $model = new $class_model('search');

        $model->unsetAttributes();

        if (isset($_GET['id_user']))
            $model->id_user = intval($_GET['id_user']);

        if (isset($_POST[$class_model]))
            $model->attributes = $_POST[$class_model];

        $this->render('/admin/order/index', array(
            'model' => $model,
        ));
    }

    public function actionView($id) {

        $model = $this->loadModel($id);

        // @TODO: model-criteria-with
        $rawProduct = Yii::app()->db->createCommand()
                ->select('o.*, s.code as size')
                ->from('{{mod_order_product}} o')
                //->join('{{mod_catalog_product}} p', 'p.id=o.id_product')
                ->leftJoin('{{mod_catalog_field_size}} s', 'o.id_field_size=s.id_field_size')
                ->where('o.id_order = :id_order')
                ->bindParam(":id_order", $id, PDO::PARAM_STR)
                ->queryAll();

        $rawStatus = OrderStatus::model()->findAll(array(
            'condition' => 'id_order = :id_order',
            'order' => 'time_created DESC',
            'params' => array(':id_order' => $id)
        ));

        $this->render('/admin/order/view', array(
            'model' => $model,
            'rawStatus' => $rawStatus,
            'rawProduct' => $rawProduct,
        ));
    }

    public function actionUpdate($id)
    {
        $class_model = "OrderItem";
        $class_status = "OrderStatus";

        $model = $this->loadModel($id);
        $model->scenario = 'update';

        if (isset($_POST[$class_model]))
        {
            $model->attributes = $_POST[$class_model];

            if ($model->save())
            {
                $orderStatus = new $class_status;
                $orderStatus->id_order = $model->id_order;
                $orderStatus->id_order_status = $model->id_order_status;
                $orderStatus->comment = $model->comment;
                $orderStatus->send_notice = $model->sendNotice;
                $orderStatus->save();

                $status = $class_status::getStatus($model->id_order_status);
                $message = Yii::t('order', 'Order #{id}: status were changed to `{status}`', array('{id}' => $model->id_order, '{status}' => $status));

                Yii::app()->user->setFlash('success', $message);

                if (!empty($model->comment))
                    $message .= "<br>".Yii::t('site', 'Comment').": ".$model->comment;

                OrderModule::sendMail(
                    $model->contact_email,
                    Yii::t('order', 'Order #{id}', array('{id}'=>$model->id_order)),
                    $message
                );

                $this->redirect(array('view', 'id' => $model->id_order));
            }
        }

        $this->render('/admin/order/update', array(
            'model' => $model,
        ));
    }

    public function actionDelete($id) {
        $this->loadModel($id)->delete();
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function loadModel($id) {
        $model = OrderItem::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }


}
