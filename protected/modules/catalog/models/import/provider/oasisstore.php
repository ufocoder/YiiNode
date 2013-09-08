<?php

namespace modules\catalog\models\import\provider;

class oasisStore extends \modules\catalog\models\import\ImporterStore
{
    protected $providerName = "oasis";

    protected function procArticle($article)
    {
        $id_product = null;
        $id_size = null;

        if (preg_match("/^([0-9a-zA-Z]{7})([2-4]XL|[a-zA-Z]+)$/i", $article, $matches))
        {
            if (!empty($matches[2])){
                $size = $matches[2];
                $article = $matches[1];
                $id_size = !empty($size)?$this->getAttribute("size", $size):null;
            }else
                $article = $matches[0];
        }

        if (!empty($this->product_schema[$article])){
            $id_product = $this->product_schema[$article];
        }

        return array(
            'id_product' => $id_product,
            'id_size' => $id_size,
        );
    }
}