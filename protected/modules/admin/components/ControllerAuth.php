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