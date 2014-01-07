<?php

class ProfileViewAction extends CAction {

    public function run($id) {

        $model = OrderItem::model()->findByPk($id, array(
            'condition' => 'id_user = :id_user',
            'params' => array(
                ':id_user' => Yii::app()->user->id
            )
        ));

        if (empty($model))
            throw new CHttpException(Yii::t('error', 'Selected order is not exists!'));

        $rawProduct = Yii::app()->db->createCommand()
                ->select('o.*, p.title, p.article, p.preview, p.image, s.code as size')
                ->from('{{db_order_product}} o')
                ->join('{{db_catalog_product}} p', 'p.id=o.id_product')
                ->leftJoin('{{db_catalog_field_size}} s', 'o.id_size=s.id')
                ->where('o.id_order = :id_order')
                ->bindParam(":id_order", $id, PDO::PARAM_STR)
                ->queryAll();

        $rawStatus = OrderStatus::model()->findAll(
            array(
                'condition' => 'id_order = :id_order',
                'params' => array(
                    ':id_order' => $id
                )
            )
        );

        $this->getController()->render('view', array(
            'model' => $model,
            'rawStatus' => $rawStatus,
            'rawProduct' => $rawProduct,
        ));
    }

}