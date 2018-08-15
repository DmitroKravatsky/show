<?php

namespace common\components;

use backend\models\BackendUser;
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

    /**
     * Sends letter to user
     * @param BackendUser $user
     * @return bool
     */
    public function sendMailToUser(BackendUser $user)
    {
        return $this->run(
            'verifyUser',
            ['user' => $user],
            Yii::$app->params['adminEmail'],
            $user->email,
            Yii::t('app', 'Verification')
        );
    }
}