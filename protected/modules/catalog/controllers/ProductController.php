<?php

class ProductController extends Controller
{
    public $defaultAction = "Product";

    public function actionIndex($id)
    {
        $id = intval($id);
        $parents = array();

        $model = CatalogProduct::model()->published()->findByPk($id);

        if (empty($data_product))
            throw new CHttpException(404, Yii::t('site', 'Указанного товара не существует!'));

        $fields = $data_product->getFields();

        $settings   = new \modules\catalog\models\Settings;
        $order_noremain = \Setting::getItem('order_noremain', 'catalog', $settings->values('order_noremain', 'default'));

        /*
        $category =  \modules\catalog\models\Category::model()->findByPK($data_product['id_category']);
        if (!empty($category)){
            $parent = $category;
            while($parent = $parent->parent)
                $parents[] = $parent;
        }
        */

        $store_pair = modules\catalog\models\ProductStore::model()->findAll(array(
            'condition' => 'id_product=:id_product',
            'params' => array(
              ':id_product' => $id
            )
        ));

        $store_size = array();
        foreach($store_pair as $item)
            if (!empty($item->id_size) && !empty($item->id_store) && !empty($item->value))
                  $store_size[$item->id_size][$item->id_store] = $item->value;

        $this->render('/product/view', array(
            //'category'  => $category,
            'fields'    => $fields,
            // 'parents'   => $parents,
            'product'   => $data_product,
            //
            'store_size' => $store_size,
            'order_noremain' => $order_noremain
        ));
    }

}