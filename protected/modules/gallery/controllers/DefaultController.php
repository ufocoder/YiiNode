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
        $image = GalleryImage::model()->node()->category();

        $categories = GalleryCategory::model()->node()->published()->findAll(array('index'=>'id_gallery_category'));

        if (!isset($categories[$id]))
            throw new CHttpException(404, 'The requested page does not exist.');

        $dataProvider = new CActiveDataProvider($image, array(
            'criteria' => array(
                'params'=>array(':id_gallery_category'=>$id)
            ),
            'pagination' => array(
                'pageSize' => $pager,
                'pageVar'  => 'page'
            )
        ));

        $this->render('/category', array(
            'id_category'   => $id,
            'categories'    => $categories,
            'dataProvider'  => $dataProvider
        ));
    }

}
