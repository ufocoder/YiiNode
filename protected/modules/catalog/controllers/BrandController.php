<?php

class BrandController extends ControllerFrontEnd
{
    public $defaultAction = "Product";

    public function behaviors() {
        return array(
            'Meta' => array(
                'class' => 'ext.meta.MetaControllerBahavior'
            )
        );
    }


    public function actionProduct($id = 0)
    {
        $brands     = array();
        $brand      = new modules\catalog\models\Brand;
        $product    = new modules\catalog\models\Product;
        $settings   = new modules\catalog\models\Settings;

        $brand              = $brand::model()->findByPk($id);
        $product_data       = $product::model()->published()->brand($id);
        $product_criteria   = $product_data->getDbCriteria();

        $filter = $product->getFilter();
        $product_criteria->mergeWith($filter['criteria']);

        $order_noremain = Setting::getItem('order_noremain', 'catalog', $settings->values('order_noremain', 'default'));
        
        $size = Setting::getItem('pager', 'catalog', $settings->values('pager', 'default'));
        $size = $filter['pager'];

        $product_count = $product_data->count();
        $product_criteria->limit = $size;

        $pages=new CPagination($product_count);
        $pages->pageSize = $size;
        $pages->applyLimit($product_criteria);

        $this->render('/brands', array(
            'brand'     => $brand,
            'brands'    => $brands,
            'filter'    => $filter,
            'products'  => $product::model()->with('color')->with('sizes')->findAll($product_criteria),
            'pages'     => $pages,
            //
            'order_noremain'    => $order_noremain
        ));
    }

    public function loadModel($id)
    {
        $model_class = "modules\news\models\Category";
        $model = $model_class::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, '«апрашиваема€ страница не существует');
        return $model;
    }

}