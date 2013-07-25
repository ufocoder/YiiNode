<?php

class DefaultController extends ControllerBackEnd
{
    public $title = 'Информационные блоки';

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->render('/item/view',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        if(isset($_POST['Block']))
        {
            $model->attributes = $_POST['Block'];
            if($model->save()){
                Yii::app()->user->setFlash('success', Yii::t('all', 'Form values were saved!'));
                $this->redirect(array('view','id'=>$model->id));
            }
        }

        $this->render('/item/update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $model = $this->loadModel($id);
            $model->delete();
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionIndex()
    {
        $model=new Block('search');
        $model->unsetAttributes();

        if (isset($_POST['Block']))
            $model->attributes=$_POST['Block'];

        $this->render('/item/admin',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model=Block::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='Block-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}