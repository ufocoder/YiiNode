<?php
/**
 * ��������� auth-����������
 * ���������� layout � ������� ��� �������
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class ControllerAuth extends Controller
{
    public $layout = '/layouts/form';

    /**
     * ������ ��������
     *
     * @return type
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

}