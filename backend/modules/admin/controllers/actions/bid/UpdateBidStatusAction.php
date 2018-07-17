<?php

namespace backend\modules\admin\controllers\actions\bid;

use common\models\bid\BidEntity;
use common\models\user\User;
use PHPUnit\Framework\Exception;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class UpdateBidStatusAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class UpdateBidStatusAction extends Action
{
    /**
     * Updates a status of the bid
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    public function run()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $bodyParams = \Yii::$app->request->getBodyParams();
        $id = $bodyParams['id'];
        $newStatus = $bodyParams['status'];

        try {
            $bid = BidEntity::findOne(['id' => $id]);

            if (!$bid) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->getResponse()->setStatusCode(404);

                return ['status' => 404, 'message' => 'Bid not found'];
            }
            $bid->setScenario($bid::SCENARIO_UPDATE_BID_STATUS);
            $bid->setAttribute('status', $newStatus);

            if ($bid->getDirtyAttributes()) {
                if (!$bid->validate()) {
                    \Yii::$app->response->setStatusCode(422);
                    throw new UnprocessableEntityHttpException();
                }
                $user = User::findOne(['id' => $bid->created_by]);
                $transaction = \Yii::$app->db->beginTransaction();
                if ($bid->save()) {
                    if ($user->email) {
                        \Yii::$app->sendMail->run(
                            '@common/views/mail/sendBidStatus-html.php',
                            ['email' => $user->email, 'status' => $bid->status],
                            \Yii::$app->params['supportEmail'], $user->email, 'status is change'
                        );
                        $transaction->commit();
                        return ['status' => 200, 'message' => 'Status was updated'];

                    } elseif ($user->phone_number) {
                        \Yii::$app->sendSms->run('Ваша заявка обрела статус' . $bid->status, $user->phone_number);
                        $transaction->commit();
                        return ['status' => 200, 'message' => 'Status was updated'];

                    }
                }
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            \Yii::$app->response->setStatusCode($e->getCode());
            \Yii::error($e->getMessage());
        }

    }
}