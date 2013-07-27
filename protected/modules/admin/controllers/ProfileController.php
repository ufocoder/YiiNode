<?php
/**
 * Admin module - Current user profile
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class ProfileController extends ControllerAdmin
{
    /**
     * View profile [index action]
     */
    public function actionIndex()
    {
        $this->render('/profile/index',array(
            'model' => $this->loadModel()
        ));
    }

    /**
     * Update profile
     */
    public function actionUpdate()
    {
        $profile = $this->loadModel();
        $profile->scenario = 'profile';

        if (isset($_POST['User']))
        {
            $profile->attributes=$_POST['User'];
            if($profile->save())
                $this->redirect(array('index'));
        }

        $this->render('/profile/update',array(
            'model' => $profile
        ));
    }

    /**
     * Change password
     */
    public function actionChangepassword()
    {
        $user = $this->loadModel();

        $class = "FormChangePassword";
        $model = new $class;
        $model->scenario = 'change';

        if(isset($_POST[$class]))
        {
            $model->attributes=$_POST[$class];
            if($model->validate())
            {
                $new_password = User::model()->findbyPk(Yii::app()->user->id);
                $new_password->password = Yii::app()->user->encrypting($model->password);
                $new_password->activekey = Yii::app()->user->encrypting(microtime().$model->password);
                if ($new_password->save())
                    Yii::app()->user->setFlash('success', Yii::t("site", "New password is saved."));

                $this->redirect(array("index"));
            }
        }
        $this->render('/profile/changepassword', array('model'=>$model));

    }

    /**
     *
     */
    public function loadModel()
    {
        $model = User::model()->findByPk(Yii::app()->user->id);
        if($model===null)
            $this->redirect(Yii::app()->user->loginUrl);

        return $model;
    }

}