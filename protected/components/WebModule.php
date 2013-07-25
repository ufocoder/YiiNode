<?php
/**
 * Base module
 *
 * @author Sergei Ivanov <xifrin@gmail.com>
 * @copyright 2013
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class WebModule extends CWebModule
{
    /**
     * Module initialize
     */
    public function init()
    {
        parent::init();

        $moduleName = $this->getName();

        $this->setImport(array(
            $moduleName.'.models.*',
            $moduleName.'.components.*',
        ));
    }

    /**
     * Module inner route
     */
    public function route()
    {
        $moduleName = $this->getName();
        return array(
            '/'                                 => $moduleName.'/default/index',
            '/<id:\d+>'                         => $moduleName.'/default/view',
            '/<controller:\w+>'                 => $moduleName.'/<controller>/index',
            '/<controller:\w+>/<id:\d+>'        => $moduleName.'/<controller>/view',
            '/<controller:\w+>/<action:\w+>'    => $moduleName.'/<controller>/<action>',
            '/<controller:\w+>/<action:\w+>/*'  => $moduleName.'/<controller>/<action>',
        );
    }

    /**
     * Static send mail method
     *
     * @param type $email
     * @param type $subject
     * @param type $message
     *
     * @return type результат отправки
     */
    public static function sendMail($email, $subject, $message)
    {
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);

        $mail = new YiiMailMessage;
        $mail->view = 'template';
        $mail->addTo($email);
        $mail->from = Yii::app()->params['robotEmail'];
        $mail->subject = $subject;
        $mail->setBody(array(
            'title' => $subject,
            'content' => $message
        ), 'text/html');

        Yii::app()->mail->getTransport()->setExtraParams(null);

        return Yii::app()->mail->send($mail);
    }

}