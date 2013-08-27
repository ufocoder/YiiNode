<?php

class DefaultController extends Controller
{
    /**
     * View categories and images
     */
    public function actionIndex()
    {
        $pager  = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'pager', GallerySetting::values('pager', 'default'));

        $categories = GalleryCategory::model()->node()->published()->findAll();
        $image = GalleryImage::model()->node()->withoutcategory();

        $dataProvider = new CActiveDataProvider($image, array(
            'pagination' => array(
                'pageSize' => $pager,
                'pageVar'  => 'page'
            )
        ));

        $this->render('/index', array(
            'categories'    => $categories,
            'dataProvider'  => $dataProvider
        ));
    }

    /**
     * View category
     */
    public function actionCategory($id)
    {
        $pager  = Yii::app()->getNodeSetting(Yii::app()->getNodeId(), 'pager', GallerySetting::values('pager', 'default'));

        $image = GalleryImage::model()->node()->category(array('params'=>array('id_gallery_category'=>$id)));

        $dataProvider = new CActiveDataProvider($image, array(
            'pagination' => array(
                'pageSize' => $pager,
                'pageVar'  => 'page'
            )
        ));

        $this->render('/category', array(
            'categories'    => $categories,
            'dataProvider'  => $dataProvider
        ));
    }

}
