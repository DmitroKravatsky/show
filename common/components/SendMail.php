<?php

namespace common\components;

use yii\base\Component;
use Yii;

/**
 * Class SendMail
 * @package common\components
 */
class SendMail extends Component
{
    /**
     * Method of sending a letter to the user's mail
     * @param $view
     * @param $params
     * @param $from
     * @param $to
     * @param $subject
     * @return bool
     */
    public function run($view, $params, $from, $to, $subject): bool
    {
        return Yii::$app->mailer->compose($view, $params)
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }
}