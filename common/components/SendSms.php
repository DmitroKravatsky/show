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
class sendSms extends Component
{
    public function run($message, $phoneNumber)
    {
        /** @var  $aws \fedemotta\awssdk\AwsSdk
         *  @var  $sns \Aws\Sns\SnsClient */
//        var_dump(1);exit;

        $aws = Yii::$app->awssdk->getAwsSdk();
        $sns = $aws->createSns();

        $sns->publish([
            'Message' => $message,
            'PhoneNumber' => $phoneNumber
        ]);
    }
}