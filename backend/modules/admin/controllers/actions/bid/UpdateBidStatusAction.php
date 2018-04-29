<?php

namespace backend\modules\admin\controllers\actions\bid;


use common\models\bid\BidEntity;
use common\models\user\User;
use yii\base\Action;

class UpdateBidStatusAction extends Action
{
    public function run($id)
    {
        $bid = BidEntity::findOne(['id' => $id]);
        $bid->setScenario($bid::SCENARIO_UPDATE_BID_STATUS);
        $bid->load(\Yii::$app->request->post());

        if ($bid->getDirtyAttributes() && $bid->validate()) {
            $user = User::findOne(['id' => $bid->created_by]);
            $transaction = \Yii::$app->db->beginTransaction();
            if ($bid->save()) {
                if ($user->phone_number) {
                    \Yii::$app->sendSms->run('Ваша заявка обрела статус' . $bid->status, $user->phone_number);
                    $transaction->commit();
                    return $this->controller->redirect(['bid/detail', 'id' => $id]);

                } elseif ($user->email) {
                    \Yii::$app->sendMail->run(
                        '@common/views/mail/sendBidStatus-html.php',
                        ['email' => $user->email, 'status' => $bid->status],
                        \Yii::$app->params['supportEmail'], $user->email, 'status is change'
                    );
                    $transaction->commit();
                    return $this->controller->redirect(['bid/detail', 'id' => $id]);
                }
            }
        }
        return $this->controller->redirect(['bid/detail', 'id' => $id]);
    }
}