<?php
/**
 * Auth controller component
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class ControllerAuth extends Controller
{
    /**
     * layout form
     */
    public $layout = '/layouts/form';

    // Bootstrap preload
    public function beforeAction($action)
    {
        Yii::app()->getComponent("bootstrap");

        return true;
    }

    /**
     * @return type filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

}