<?php

namespace common\components;
use Yii;
use yii\base\Component;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 17.02.18
 * Time: 10:58
 */
class SendSms extends Component
{
    public function run($message, $phoneNumber)
    {
        /** @var  $aws \fedemotta\awssdk\AwsSdk
         *  @var  $sns \Aws\Sns\SnsClient */

        $aws = Yii::$app->awssdk->getAwsSdk();
        $sns = $aws->createSns();

        $sns->publish([
            'Message' => $message,
            'PhoneNumber' => $phoneNumber
        ]);
    }
}