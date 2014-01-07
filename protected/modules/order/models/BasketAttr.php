<?php

class BasketAttr extends Basket {

    protected static $_attributes = array(
        "attribute 1",
        "attribute 2"
    );

    // имя корзины
    protected static $name = "default";

    // имена товаров
    protected static function getNames($id, $attributes = array()) {
        $names = array(
            array(
                "name" => "id:" . $id . ";",
                "attr" => array()
            )
        );

        foreach ($attributes as $attribute => $values) {
            if (in_array($attribute, static::$_attributes)) {
                $n = array();
                foreach ($values as $value){
                    foreach ($names as $item) {
                        $n[] = array(
                            "name" => $item['name'] . $attribute . ":" . $value . ";",
                            "attr" => array_merge(
                                array($attribute => $value), $item['attr']
                            )
                        );
                    }
                }

                $names = $n;
            }
        }

        return $names;
    }

    /**
     * Уменьшение значения выбранных единиц продукции в корзине
     *
     * @param integer $id
     * @param integer $count
     *
     * @return json
     */
    public static function productReduce($id, $hash = null, $count = 1, array $attributes = array()) {
        if (empty($id) && empty($hash) || empty($count))
            return false;

        $id = intval($id);
        $count = intval($count);

        $list = self::getList();

        if (isset($list[$hash])) {
            if (!empty($list[$hash]))
                $list[$hash]['count']-= $count;
            if ($list[$hash]['count'] < 0)
                $list[$hash]['count'] = 0;
        }
        else {
            $names = self::getNames($id, $attributes);
            foreach ($names as $item) {
                $name = md5($item['name']);
                if (!empty($list[$name]))
                    $list[$name]['count']-= $count;
                if ($list[$name]['count'] < 0)
                    $list[$name]['count'] = 0;
            }
        }

        self::setList($list);

        return true;
    }

    /**
     * Устанавливаем значения выбранных единиц продукции в корзине
     *
     * @param integer $id
     * @param integer $count
     *
     * @return json
     */
    public static function productSet($id, $hash = null, $count = 1, array $attributes = array()) {
        if (empty($id) && empty($hash))
            return false;

        $id = intval($id);
        $count = intval($count);
        $count = ($count < 0) ? 0 : $count;

        $list = self::getList();

        if (isset($list[$hash])) {
            $list[$hash]['count'] = $count;
        } else {
            $names = self::getNames($id, $attributes);
            foreach ($names as $item)
                $list[md5($item['name'])] = array(
                    "id_rp" => $id,
                    "attributes" => $item['attr'],
                    "count" => $count
                );
        }

        self::setList($list);

        return true;
    }

    /**
     * Увеличение значения выбранных единиц продукции в корзине
     *
     * @param integer $id
     * @param integer $count
     *
     * @return json
     */
    public static function productAdd($id, $hash = null, $count = 1, $attributes = array()) {

        if (empty($id) && empty($hash))
            return false;

        $id = intval($id);
        $count = intval($count);

        $list = self::getList();

        if (isset($list[$hash])) {
            $list[$hash]['count']+= $count;
        } else {
            $names = self::getNames($id, $attributes);

            foreach ($names as $item) {
                $name = md5($item['name']);
                if (empty($list[$name]))
                    $list[$name] = array(
                        "id" => $id,
                        "attributes" => $item['attr'],
                        "count" => $count
                    );
                else{
                    $list[$name]['count'] += $count;
                }
            }
        }

        self::setList($list);

        return true;
    }

    /**
     * Удаление значения выбранных единиц продукции в корзине
     *
     * @param integer $id
     *
     * @return json
     */
    public static function productDelete($id, $hash = null, array $attributes = array()) {
        if (empty($id) && empty($hash))
            return false;

        $id = intval($id);

        $list = self::getList();

        if (isset($list[$hash])) {
            unset($list[$hash]);
        } else {
            $names = self::getNames($id, $attributes);
            foreach ($names as $item)
                unset($list[md5($item['name'])]);
        }

        self::setList($list);

        return true;
    }

    /**
     * Получение текущего заказа
     *
     * @return string
     */
    public static function getCurrent() {

        $count_list = 0;
        $list = self::getList();

        if (!empty($list))
            foreach ($list as $item)
                $count_list += $item['count'];

        return array(
            "list" => $list,
            "count" => $count_list
        );
    }

}