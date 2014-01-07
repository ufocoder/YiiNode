<?php
/**
 * Feedback module
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FeedbackModule extends WebModule
{
    /**
     * Flag module is node type
     */
    public $nodeModule = true;

    /**
     * Module initialize
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Returns the description of this module.
     * @return string the description of this module.
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Returns the version of this module.
     * The default implementation returns '1.0'.
     * You may override this method to customize the version of this module.
     * @return string the version of this module.
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * Правила маршрутизации
     */
    public function route()
    {
        return array(
            '/captcha/*'    => 'feedback/default/captcha',
            '/'             => 'feedback/default/index',
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

        $feedbackEmail = Yii::app()->getSetting('feedbackEmail');

        $from = !empty($feedbackEmail)?$feedbackEmail:Yii::app()->params['robotEmail'];

        $mail = new YiiMailMessage;
        $mail->view = 'template';
        $mail->addTo($email);
        $mail->from = $from;
        $mail->subject = $subject;
        $mail->setBody(array(
            'title' => $subject,
            'content' => $message
        ), 'text/html');

        Yii::app()->mail->getTransport()->setExtraParams(null);

        return Yii::app()->mail->send($mail);
    }

}