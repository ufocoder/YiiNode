<?php
    $this->layout = '//layouts/page';

    if (!empty($category->title)){
        $this->pageTitle = $category->title;
        $this->breadcrumbs = array(
            Yii::t('site', 'Catalog')=>array('/catalog')
        );
        foreach($parents as $parent)
            $this->breadcrumbs[$parent->title] = array('/catalog/category', 'id'=>$parent->id);
        $this->breadcrumbs[$category->title] = array('/catalog/category', 'id'=>$category->id);
    }
    else{
        $this->pageTitle = Yii::t('catalog', 'Catalog');
        $this->breadcrumbs = array(
            Yii::t('site', 'Catalog')
        );
    }

    $this->renderPartial('/category/list', array('categories'=>$categories));
    $this->renderPartial('/brands/list', array('brands'=>$brands));
    $this->renderPartial('/product/list', array(
        'category'  => $category,
        'filter'    => $filter,
        'products'  => $products,
        'pages'     => $pages
    ));
?>