<?php

class DefaultController extends Controller
{

    public function actionIndex()
    {

        $query_dictionary = Yii::app()->db->createCommand()
                ->select('id_field_size, code')
                ->from("{{mod_catalog_field_size}}")
                ->queryAll();

        $sizes = array();
        foreach ($query_dictionary as $item)
            $sizes[$item['id_field_size']] = $item['code'];

        $this->render('/index', array(
            'basket' => BasketAttrProduct::getCurrent(),
            'sizes' => $sizes
        ));
    }

}