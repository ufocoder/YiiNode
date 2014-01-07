<?php

class SendController extends Controller
{

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'testLimit' => '3',
            ),
        );
    }

    public function actionIndex()
    {
        $model_class    = "OrderItem";
        $class_discount = "OrderDiscount";
        $class_status   = "OrderStatus";
        $class_product  = "OrderProduct";
        $class_basket   = "BasketAttrProduct";

        $model = new $model_class;

        if (!Yii::app()->user->isGuest)
        {
            $userData = Yii::app()->user->getModel();

            $model->person_name     = !empty($userData->profile->firstname)?$userData->profile->firstname:null;
            $model->contact_phone   = !empty($userData->profile->phone)?$userData->profile->phone:null;
            $model->contact_email   = !empty($userData->email)?$userData->email:null;

            $discount = new $class_discount;
            $discount = $discount->findByPk($userData->id_user);
            $model->discount = isset($discount->discount)?$discount->discount:0;
        }

        $basket_product = $class_basket::getCurrent();

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form-order') {
            $model->scenario = 'ajax';
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (empty($basket_product['total']['count']))
            Yii::app()->request->redirect(Yii::app()->createUrl('/order'), true);

        if (isset($_POST[$model_class])) {

            $model->attributes = $_POST[$model_class];

            if ($model->validate())
            {
                $delivery = $model->values('delivery_type', 'data');
                $model->count = $basket_product['total']['count'];
                $model->cost_product = $basket_product['total']['cost'];
                $model->cost_delivery = $delivery[$model->delivery_type]['price'];

                if (empty($model->id_user_manager))
                    $model->id_user_manager = null;

                $model->id_order_status = $class_status::STATUS_SEND;

                if ($model->save()) {
                    $db = Yii::app()->db;

                    foreach ($basket_product['product'] as $id_product => $product) {
                        $orderProduct = new $class_product;
                        $orderProduct->id_order = $model->id_order;
                        $orderProduct->id_product = $product['id'];
                        $orderProduct->id_field_size = !empty($product['attributes']['size']) ? $product['attributes']['size'] : null;
                        $orderProduct->article = $product['data']['article'];
                        $orderProduct->title = $product['data']['title'];
                        $orderProduct->price = $product['data']['price'];
                        $orderProduct->count = $product['count'];
                        $orderProduct->save();
                    }

                    $orderStatus = new $class_status;
                    $orderStatus->id_order = $model->id_order;
                    $orderStatus->id_order_status = $class_status::STATUS_SEND;
                    $orderStatus->send_notice = 1;
                    $orderStatus->save();

                    $noticeAdmin = Yii::app()->getSetting('orderNoticeAdmin', $model->values('orderNoticeAdmin', 'default'));
                    $noticeManager = Yii::app()->getSetting('orderNoticeManager', $model->values('orderNoticeManager', 'default'));
                    $noticeEmail = Yii::app()->getSetting('orderNoticeEmail', $model->values('orderNoticeEmail', 'default'));
                    $noticeUser  = Yii::app()->getSetting('orderNoticeUser', $model->values('orderNoticeUser', 'default'));

                    // Уведомляем пользователя
                    if (!empty($noticeUser))
                    {
                        if (Yii::app()->user->isGuest){
                        $message = Yii::t('order', 'Your order get #{id} number. Watch for changing the status of the order on email', array(
                            '{id}' => $model->id
                            ));
                        }
                        else{
                            $message  = Yii::t('order', 'Your order get #{id} number. Watch for changing the status of the order on email or your profile', array(
                                '{id}' => $model->id
                            ));
                        }

                        OrderModule::sendMail(
                            $model->contact_email,
                            Yii::t('order', 'Thank you made the order on {sitename}!', array(
                                '{sitename}'=>Yii::app()->name
                            )),
                            $message
                        );
                    }
                    else
                        $message = Yii::t('order', 'Your order is accepted and it will be processed in a short time');

                    // представление для письма
                    $noticeTitle = Yii::t('order', 'New order on {sitename}',array(
                        '{sitename}' => Yii::app()->name
                    ));
                    $noticeView = $this->renderPartial('/admin/_mail', array(
                        'model' => $model,
                        'basket' => $basket_product
                    ), true);

                    // уведомляем администратора
                    if (!empty($noticeAdmin) && !empty($noticeEmail)){
                         OrderModule::sendMail(
                            $noticeEmail,
                            $noticeTitle,
                            $noticeView
                        );
                    }

                    // уведомляем менеджера
                    // уведомляем администратора
                    if (!empty($noticeManager) && !empty($noticeEmail)){
                         OrderModule::sendMail(
                            $noticeEmail,
                            $noticeTitle,
                            $noticeView
                        );
                    }

                    $class_basket::clearCurrent();
                    Yii::app()->user->setFlash('success', $message);
                    $this->render('/message', array(
                        'message' => $message
                    ));
                    Yii::app()->end();
                }
            }
        }

        $this->render('/send', array(
            'model' => $model,
            'basket' => $basket_product
        ));
    }

}