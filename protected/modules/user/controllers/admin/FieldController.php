<?php

class FieldController extends ControllerAdmin
{
    public $layout = "application.modules.admin.views.layouts.column1";

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;
    private static $_widgets = array();

    /**
     * Displays a particular model.
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $class  = 'ProfileField';
        $model  = new $class;

        if(isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];

            if($model->validate())
            {
                $scheme = get_class(Yii::app()->db->schema);

                $sql = 'ALTER TABLE '.Profile::model()->tableName().' ADD `'.$model->varname.'` ';
                $sql .= $this->fieldType($model->field_type);

                if ($model->field_type!='TEXT'
                    && $model->field_type!='DATE'
                    && $model->field_type!='BOOL'
                    && $model->field_type!='BLOB'
                    && $model->field_type!='BINARY'
                )
                    $sql .= '('.$model->field_size.')';

                $sql .= ' NOT NULL ';

                if ($model->field_type!='TEXT'&&$model->field_type!='BLOB' || $scheme != 'CMysqlSchema')
                {
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
                $this->redirect(array('view','id'=>$model->id_user_field));
            }
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate($id)
    {
        $class = 'ProfileField';
        $model = $this->loadModel($id);

        if(isset($_POST[$class])){
            $model->attributes = $_POST[$class];
            if($model->save())
                $this->redirect(array('view', 'id' => $model->id_user_field));
        }

        $this->render('update',array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete($id)
    {
        $scheme = get_class(Yii::app()->db->schema);
        $model = $this->loadModel($id);

        if ($scheme == 'CSqliteSchema')
        {
            $attr = Profile::model()->attributes;
            unset($attr[$model->varname]);
            $attr = array_keys($attr);
            $connection=Yii::app()->db;
            $transaction=$connection->beginTransaction();
            $status=true;
            try
            {
                $connection->createCommand(
                        "CREATE TEMPORARY TABLE ".Profile::model()->tableName()."_backup (".implode(',',$attr).")"
                    )->execute();

                    $connection->createCommand(
                        "INSERT INTO ".Profile::model()->tableName()."_backup SELECT ".implode(',',$attr)." FROM ".Profile::model()->tableName()
                    )->execute();

                    $connection->createCommand(
                        "DROP TABLE ".Profile::model()->tableName()
                    )->execute();

                    $connection->createCommand(
                        "CREATE TABLE ".Profile::model()->tableName()." (".implode(',',$attr).")"
                    )->execute();

                    $connection->createCommand(
                        "INSERT INTO ".Profile::model()->tableName()." SELECT ".implode(',',$attr)." FROM ".Profile::model()->tableName()."_backup"
                    )->execute();

                    $connection->createCommand(
                        "DROP TABLE ".Profile::model()->tableName()."_backup"
                    )->execute();

                    $transaction->commit();
                }
                catch(Exception $e){
                    $transaction->rollBack();
                    $status=false;
                }
                if ($status) {
                    $model->delete();
                }

            } else {
                $sql = 'ALTER TABLE '.Profile::model()->tableName().' DROP `'.$model->varname.'`';
                if ($model->dbConnection->createCommand($sql)->execute()) {
                    $model->delete();
                }
            }

        if (!isset($_POST['ajax']))
            $this->redirect(array('/admin/user/field'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model=new ProfileField('search');
        $model->unsetAttributes();

        if(isset($_GET['ProfileField']))
            $model->attributes=$_GET['ProfileField'];

        $this->render('/admin/field/index',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel($id)
    {
        $model = ProfileField::model()->findbyPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));

        return $model;
    }

    /**
     * MySQL field type
     * @param $type string
     * @return string
     */
    public function fieldType($type) {
        $type = str_replace('UNIX-DATE','INTEGER',$type);
        return $type;
    }

    /*
    public static function getWidgets($fieldType='')
    {
        $basePath=Yii::getPathOfAlias('application.modules.user.components');

        $widgets = array();
        $list = array(''=>Yii::t('site', 'No'));

        if (self::$_widgets) {
            $widgets = self::$_widgets;
        } else {
            $d = dir($basePath);
            while (false !== ($file = $d->read())) {
                if (strpos($file,'UW')===0) {
                    list($className) = explode('.',$file);
                    if (class_exists($className)) {
                        $widgetClass = new $className;
                        if ($widgetClass->init()) {
                            $widgets[$className] = $widgetClass->init();
                            if ($fieldType) {
                                if (in_array($fieldType,$widgets[$className]['fieldType'])) $list[$className] = $widgets[$className]['label'];
                            } else {
                                $list[$className] = $widgets[$className]['label'];
                            }
                        }
                    }
                }
            }
            $d->close();
        }
        return array($list,$widgets);
    }

    */


    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='field-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
