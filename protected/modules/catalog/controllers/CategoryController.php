<?php

class CategoryController extends Controller
{

    public function actionIndex($id = 0)
    {
        $brands     = array();
        $parents    = array();
        $category   = new modules\catalog\models\Category;
        $categories = array();
        $brand      = new modules\catalog\models\Brand;
        $product    = new modules\catalog\models\Product;
        $settings   = new modules\catalog\models\Settings;

        $tree       = array();
        $selectedId = array();

        $product_data      = $product::model()->published();
        $product_criteria  = $product_data->getDbCriteria();

        $filter = $product->getFilter();
        $product_criteria->mergeWith($filter['criteria']);


        $order_noremain = Setting::getItem('order_noremain', 'catalog', $settings->values('order_noremain', 'default'));

        $size = Setting::getItem('pager', 'catalog', $settings->values('pager', 'default'));
        $size = $filter['pager'];

        if (empty($id))
        {
            $categories = $category->roots()->published()->findAll(array('order'=>'position'));
            $tree = $categories;
            $product_criteria->mergeWith(array('condition'=>'t.id IS NULL AND t.enabled = 1'));
            $brands = $brand::model()->slider()->findAll();
        }
        else{

            $tree = $category->roots()->published()->findAll(array('order'=>'position'));
            $category = $category::model()->published()->findByPK($id);

            if (!empty($category))
            {
                $categories = $category->children()->published()->findAll();

                // make tree and parent list
                $parent = $category;
                $selectedId[] = $parent->id;

                while($parent = $parent->parent){
                    $selectedId[] = $parent->id;
                    $tree = array_merge($tree, $category->published()->findAll(array('condition'=>'id_parent = '.$parent->id, 'order'=>'lft')));
                    array_unshift($parents, $parent);
                }


                $tree = array_merge($tree, $category->published()->findAll(array('condition'=>'enabled = 1 AND id_parent = '.$category->id, 'order'=>'lft')));
            }else
                throw new CHttpException(404, 'Указанной категории не существует!');

            $product_criteria->mergeWith(array(
                'join' => 'INNER JOIN `{{catalog_category_product}}` `cp` ON `t`.`id` = `cp`.`id_product`',
                'condition'=>'cp.id_category = :id_caterogy', 'params'=>array(':id_caterogy'=>$id)
            ));
        }

        $product_criteria->limit = $size;
        $product_criteria->mergeWith(array(
             'group' => 't.id'
        ));

        $sql = "SELECT COUNT(DISTINCT(t.id)) as `count` FROM {{catalog_product}} `t` "
              ."INNER JOIN {{catalog_category_product}} `cp` ON `t`.`id` = `cp`.`id_product` "
              ."WHERE ((t.enabled = 1 AND t.price != 0) AND (cp.id_category = ".(int)$id."))";

        $command = \Yii::app()->db->createCommand($sql)->queryRow();
        $count_product = $command['count'];

        $pages=new CPagination($count_product);
        $pages->pageSize = $size;
        $pages->applyLimit($product_criteria);

        $products = $product::model()->with('color')->with('sizes')->findAll($product_criteria);

        Yii::app()->params['catagory_tree'] = $tree;
        Yii::app()->params['catagory_selectedId'] = $selectedId;

        $this->render('/category', array(
            'brands'            => $brands,
            'category'          => $category,
            'categories'        => $categories,
            'categoryParents'   => $parents,
            'filter'            => $filter,
            'products'          => $products,
            'pages'             => $pages,
            //
            'order_noremain'    => $order_noremain
        ));
    }

    public function loadModel($id)
    {
        $model_class = "modules\catalog\models\Category";
        $model = $model_class::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404, 'Запрашиваемая страница не существует');
        return $model;
    }

}