<?php

class Basket {

    protected static $name = "default";

    public static function getName() {
        return static::$name;
    }

    /**
     * Получить корзину
     *
     * @return array
     */
    protected static function getList() {

        $list = \Yii::app()->session[self::getName()];

        if (empty($list))
            $list = array();

        return $list;
    }

    /**
     * Получить корзину
     *
     * @return array
     */
    protected static function setList($list = array()) {
        \Yii::app()->session[self::getName()] = $list;
    }

    /**
     * Очистить корзину
     */
    public static function clearCurrent() {
        \Yii::app()->session[self::getName()] = array();
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
            foreach ($list as $item => $count)
                $count_list+=$count;

        return array(
            "list" => $list,
            "count" => $count_list
        );
    }

    /**
     * Уменьшение значения выбранных единиц продукции в корзине
     *
     * @param integer $id
     * @param integer $count
     *
     * @return json
     */
    public static function productReduce($id, $count = 1) {

        if (empty($id) || empty($count))
            return false;

        $list = self::getList();

        if (empty($list[intval($id)]))
            $list[intval($id)] = (intval($count));
        else
            $list[intval($id)]-= (intval($count));

        if ($list[intval($id)] <= 0)
            unset($list[intval($id)]);

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
    public static function productSet($id, $count = 1) {

        if (empty($id))
            return false;

        $list = self::getList();

        $list[intval($id)] = (intval($count));

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
    public static function productAdd($id, $count = 1) {
        if (empty($id))
            return false;

        $list = self::getList();

        if (empty($list[intval($id)]))
            $list[intval($id)] = (intval($count));
        else
            $list[intval($id)]+= (intval($count));

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
    public static function productDelete($id) {

        if (empty($id))
            return false;

        $list = self::getList();

        unset($list[intval($id)]);

        self::setList($list);

        return true;
    }

}