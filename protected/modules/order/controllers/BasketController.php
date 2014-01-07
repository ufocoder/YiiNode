<?php

class BasketController extends Controller {

    public function actionIndex()
    {
        // ajax
        if (Yii::app()->request->getIsAjaxRequest()) {

            $action = Yii::app()->request->getParam('action', 'list');
            $id_product = Yii::app()->request->getParam('id');
            $hash = Yii::app()->request->getParam('hash', null);
            $value = Yii::app()->request->getParam('value', 1);
            $attributes = Yii::app()->request->getParam('attributes', array());

            $id_product = (intval($id_product));
            $value = (floatval($value));

            $basket = "BasketAttrProduct";

            switch ($action) {
                case "add":
                    $flag = $basket::productAdd($id_product, $hash, $value, $attributes);
                    break;

                case "set":
                    $flag = $basket::productSet($id_product, $hash, $value, $attributes);
                    break;

                case "reduce":
                    $flag = $basket::productReduce($id_product, $hash, $value, $attributes);
                    break;

                case "delete":
                    $flag = $basket::productDelete($id_product, $hash, $attributes);
                    break;
            }

            $this->layout = false;
            header('Content-type: application/json');
            echo CJSON::encode(array("flag" => $flag, "data" => $basket::getCurrent(true)));
            Yii::app()->end();
        }

        $query_dictionary = \Yii::app()->db->createCommand()
                ->select('id_field_size, code')
                ->from("{{db_catalog_field_size}}")
                ->queryAll();

        $sizes = array();
        foreach ($query_dictionary as $item)
            $sizes[$item['id']] = $item['code'];

        $this->render('/index', array(
            'basket' => BasketAttrProduct::getCurrent(),
            'sizes' => $sizes,
        ));
    }

}