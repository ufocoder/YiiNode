<?php


class BasketAttrProduct extends BasketAttr {

    protected static $_current_data = array();
    protected static $_flag_current = false;
    protected static $_attributes = array(
        "size",
        "color"
    );

    // имя корзины
    protected static $name = "basket.product";

    /**
     * Получение текущего заказа
     *
     * @return string
     */
    public static function getCurrent($flag_update = false) {


        // @TODO: учитываем можно ли ложить в корзину товар, которого нет на складе
        // $settings = new \modules\catalog\models\Settings;
        // $order_noremain = \Setting::getItem('order_noremain', 'catalog', $settings->values('order_noremain', 'default'));
        $order_noremain = false;

        //
        if (self::$_flag_current != true || $flag_update) {
            $product = array();
            $total_list = array(
                "count" => 0,
                "cost" => 0
            );

            $list = self::getList();

            $ids = array();
            foreach ($list as $item)
                if (!empty($item['id']) && !in_array($item['id'], $ids))
                    $ids[] = $item['id'];

            if (!empty($ids)) {
                $query = \Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('{{mod_catalog_product}}')
                        ->where('`enabled` = 1')
                        ->where('`id_product` IN (' . implode(',', $ids) . ')')
                        ->queryAll();

                // @TODO: условия добавления в корзину
                foreach ($query as $item){
                    // $flag_residue = !empty($item['store']) || !empty($item['total']);
                    // $flag_basket = $flag_residue && $order_noremain ||  $flag_residue;

                    // if ($flag_basket)
                        $data_product[$item['id_product']] = $item;
                }

                if (empty($data_product))
                    self::clearCurrent();
                else
                    foreach ($list as $hash => $item) {
                        $id_product = $item['id'];
                        $count = $item['count'];
                        $attributes = $item['attributes'];

                        if ($count < 0
                                || empty($item['id'])
                                || empty($data_product[$id_product])
                        ) {
                            self::productDelete($id_product);
                            continue;
                        }

                        $product[$hash] = array(
                            "id" => $id_product,
                            "data" => array(
                                "article" => $data_product[$id_product]['article'],
                                "title" => $data_product[$id_product]['title'],
                                "price" => $data_product[$id_product]['price'],
                                "preview" => $data_product[$id_product]['preview'],
                                "image" => $data_product[$id_product]['image']
                            ),
                            "attributes" => $attributes,
                            "count" => $count,
                            "cost" => $count * $data_product[$id_product]['price']
                        );

                        $count = intval($count);

                        $total_list['count'] += $count;
                        $total_list['cost'] += $data_product[$id_product]['price'] * $count;
                    }
            }

            self::$_current_data = array(
                "product" => $product,
                "total" => $total_list
            );

            self::$_flag_current = true;
        }

        return self::$_current_data;
    }

}