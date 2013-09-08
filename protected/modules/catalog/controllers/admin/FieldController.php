<?php

class FieldController extends ControllerAdmin
{

    public $layout = "application.modules.admin.views.layouts.column1";

    public function actionView($id)
    {
        $this->render('/admin/field/view',array(
            'model'=>$this->loadModel($id),
        ));
    }


    public function actionCreate()
    {
        $model_class = "CatalogField";
        $model = new $model_class;

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            $scheme = get_class(Yii::app()->db->schema);

            if($model->validate())
            {
                $sql = 'ALTER TABLE '.CatalogProductField::model()->tableName().' ADD `'.$model->varname.'` ';
                $sql .= $model->field_type;
                if (
                        $model->field_type!='TEXT'
                        && $model->field_type!='DATE'
                        && $model->field_type!='BOOL'
                        && $model->field_type!='BLOB'
                        && $model->field_type!='BINARY'
                    )
                    $sql .= '('.$model->field_size.')';
                $sql .= ' NOT NULL ';

                if ($model->field_type!='TEXT' && $model->field_type!='BLOB' || $scheme!='CMysqlSchema') {
                    if ($model->default)
                        $sql .= " DEFAULT '".$model->default."'";
                    else
                        $sql .= ((
                                    $model->field_type=='TEXT'
                                    ||$model->field_type=='VARCHAR'
                                    ||$model->field_type=='BLOB'
                                    ||$model->field_type=='BINARY'
                                )?" DEFAULT ''":(($model->field_type=='DATE')?" DEFAULT '0000-00-00'":" DEFAULT 0"));
                }
                $model->dbConnection->createCommand($sql)->execute();
                $model->save();

                Yii::app()->user->setFlash('success', Yii::t('site', 'Field was created successful!'));
                $this->redirect(array('/field/index', 'id'=>$model->id_field, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }

        }

        $this->render('/admin/field/create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model_class = "CatalogField";
        $model=$this->loadModel($id);

        if(isset($_POST[$model_class]))
        {
            $model->attributes = $_POST[$model_class];
            if($model->save()){
                Yii::app()->user->setFlash('success', Yii::t('site', 'Field was created successful!'));
                $this->redirect(array('/field/index', 'id'=>$model->id_field, 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
            }
        }

        $this->render('/admin/field/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $model = $this->loadModel($id);
            $scheme = get_class(Yii::app()->db->schema);

            if ($scheme=='CSqliteSchema') {
                $attr = CatalogProductField::model()->attributes;
                unset($attr[$model->varname]);
                $attr = array_keys($attr);
                $connection=Yii::app()->db;
                $transaction=$connection->beginTransaction();
                $status=true;
                try
                {
                    $sql = '';
                    $connection->createCommand(
                        "CREATE TEMPORARY TABLE ".CatalogProductField::model()->tableName()."_backup (".implode(',',$attr).")"
                    )->execute();

                    $connection->createCommand(
                        "INSERT INTO ".CatalogProductField::model()->tableName()."_backup SELECT ".implode(',',$attr)." FROM ".CatalogProductField::model()->tableName()
                    )->execute();

                    $connection->createCommand(
                        "DROP TABLE ".CatalogProductField::model()->tableName()
                    )->execute();

                    $connection->createCommand(
                        "CREATE TABLE ".CatalogProductField::model()->tableName()." (".implode(',',$attr).")"
                    )->execute();

                    $connection->createCommand(
                        "INSERT INTO ".CatalogProductField::model()->tableName()." SELECT ".implode(',',$attr)." FROM ".CatalogProductField::model()->tableName()."_backup"
                    )->execute();

                    $connection->createCommand(
                        "DROP TABLE ".CatalogProductField::model()->tableName()."_backup"
                    )->execute();

                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    $status=false;
                }
                if ($status) {
                    $model->delete();
                }

            } else {
                $sql = 'ALTER TABLE '.CatalogProductField::model()->tableName().' DROP `'.$model->varname.'`';
                if ($model->dbConnection->createCommand($sql)->execute()) {
                    $model->delete();
                }
            }

            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/field/index', 'nodeAdmin'=>true, 'nodeId'=>Yii::app()->getNodeId()));
        }
        else
            throw new CHttpException(400, Yii::t('error', 'Invalid request. Please do not repeat this request again.'));
    }

    public function actionIndex()
    {
        $model_class = "CatalogField";
        $model=new $model_class('search');
        $model->unsetAttributes();

        if(isset($_GET[$model_class]))
            $model->attributes=$_GET[$model_class];

        $this->layout = "application.modules.catalog.views.admin.layouts.main";
        $this->render('/admin/field/index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model = CatalogField::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='Field-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}