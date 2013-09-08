<?php

class ImportController extends ControllerAdmin
{
    public $title = 'Импорт';

    protected function updateImport()
    {
        \modules\catalog\models\Category::model()->updateBrandCount();
        \modules\catalog\models\Category::model()->updateProductCount();
    }

    public function actionIndex()
    {
        $class_import   = "modules\\catalog\models\ImportTree";
        $class_category = "modules\\catalog\models\Category";

        $criteria       = array('order'=>'id_parent ASC, position ASC');

        $data_import    = $class_import::model()->findAll($criteria);
        $data_category  = $class_category::model()->findAll($criteria);

        $import = array();
        $path = array();
        foreach($data_import as $category)
        {
            if (isset($path[$category['id_parent']]))
                $path[$category['id']] = $path[$category['id_parent']]."/".$category['title'];
            else
                $path[$category['id']] = "/".$category['title'];

            $category['itemId'] = $category['id'];
            $import[] = $category;
        }


        $list = array();
        foreach($data_category as $category){
            $list[] = array(
                'id' => $category['id'],
                'itemId' => $category['id'],
                'id_parent' => $category['id_parent'],
                'title' => $category['title'],
                'image' => $category['image'],
                'import' => false,
                'drap' => false,
                'drop' => false,
            );
        }

        $data_query = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{catalog_import_schema}} s')
            ->join('{{catalog_import_tree}} t', 's.id_tree=t.id')
            ->queryAll();

        foreach($data_query as $item){
            $item['itemId'] = $item['id'];
            $item['id_parent'] = $item['id_category'];
            $item['font'] = array("background"=>"#faffc4");
            $item['import'] = true;
            $item['dropRoot'] = false;

            $item['title'] = $path[$item['id']];

            unset($item['id']);
            $list[] = $item;
        }

        $this->render('/import/index',array(
            'data_import' => $import,
            'data_category' => $list,
            'path' => $path
        ));
    }

    public function actionClearItem(){

        $id_tree = @intval($_POST['id_tree']);
        $id_category = @intval($_POST['id_category']);
        if (empty($id_tree) || empty($id_category))
            return;

        $sql = "DELETE FROM {{catalog_import_schema}} WHERE id_category=".$id_category." AND id_tree=".$id_tree;
        Yii::app()->db->createCommand($sql)->execute();

        $sql = "DELETE FROM {{catalog_category_product}} WHERE `id_category`=".$id_category;
        Yii::app()->db->createCommand($sql)->execute();

        $this->updateImport();

        echo json_encode(array('success'=>true));
    }

    public function actionClear(){
        $sql = "DELETE FROM {{catalog_import_schema}}";
        Yii::app()->db->createCommand($sql)->execute();

        $sql = "DELETE FROM {{catalog_category_import}}";
        Yii::app()->db->createCommand($sql)->execute();

        $this->updateImport();

        Yii::app()->request->redirect(Yii::app()->createUrl('catalog/import/index'), true);
    }

    public function actionSchema()
    {
        if (isset($_POST['Schema'])){
            $schema = $_POST['Schema'];

            if (empty($schema['CategoryId']) || empty($schema['ImportNodes']))
                return;

            $schema['CategoryId'] = intval($schema['CategoryId']);

            // связываем категории импорта и каталога
            $values = array();
            foreach($schema['ImportNodes'] as $value){
                $value = intval($value);
                $values[] = '('.$schema['CategoryId'].', '.$value.')';
                $sql = "DELETE FROM {{catalog_import_schema}} WHERE `id_tree` = ".$value;
                Yii::app()->db->createCommand($sql)->execute();
            }

            $sql = 'INSERT INTO {{catalog_import_schema}} (id_category, id_tree) VALUES ' . implode(',', $values);
            Yii::app()->db->createCommand($sql)->execute();

            // связываем
            foreach($schema['ImportNodes'] as $value){
                $value = intval($value);
                $sql = "DELETE FROM {{catalog_product}} SET `id_category`=".$schema['CategoryId']." WHERE `id_tree`=".$value;
                Yii::app()->db->createCommand($sql)->execute();
            }

            $this->updateImport();

            echo json_encode(array('success'=>true));
        }
    }

    public function actionUpload()
    {
        ini_set('memory_limit', '512M');
        set_time_limit (180);

        $class_form = "modules\catalog\models\import\Form";
        $class_category = "modules\catalog\models\ImportTree";
        $model = new $class_form;

        if(isset($_POST[$class_form]))
        {
            $model->attributes = $_POST[$class_form];
            $class = "modules\catalog\models\import\provider\\".$model->data_provider;
            $fileName = CUploadedFile::getInstance($model, 'data_file')->tempName;

            $importer = new $class(array(
                "category_id" => $model->category_id,
                "product_action" => $model->product_action
            ));

            if ($importer->parseFile($fileName))
                Yii::app()->user->setFlash('success', Yii::t('site', 'Form values were saved!'));
            else
                Yii::app()->user->setFlash('warning', 'Ошибка обработки файла!');
        }

        $this->render('/import/upload',array(
            'model'=>$model,
            'data_tree' => $class_category::model()->findAll(array('order'=>'lft'))
        ));
    }

}

?>