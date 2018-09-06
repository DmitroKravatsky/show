<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\models\BackendUser;
use backend\modules\admin\controllers\BidController;
use common\models\bid\BidEntity;
use common\models\user\User;
use yii\base\Action;
use yii\web\UnprocessableEntityHttpException;
use Yii;
use yii\web\Response;

/**
 * Class UpdateBidStatusAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class UpdateBidStatusAction extends Action
{
    /**@var $controller BidController */
    public $controller;

    /**
     * Updates a status of the bid
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function run()
    {
        $bodyParams = \Yii::$app->request->getBodyParams();
        $id = $bodyParams['id'];
        $newStatus = $bodyParams['status'];

        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $bid = $this->controller->findBid($id);

            $bid->setScenario($bid::SCENARIO_UPDATE_BID_STATUS);
            $bid->status = $newStatus;
            $bid->processed_by = Yii::$app->user->id;
            if (($newStatus == BidEntity::STATUS_PAID_BY_US_DONE) || ($newStatus == BidEntity::STATUS_REJECTED)) {
                $bid->processed = BidEntity::PROCESSED_YES;
            }

            if ($bid->getDirtyAttributes()) {
                if (!$bid->validate()) {
                    \Yii::$app->response->setStatusCode(422);
                    throw new UnprocessableEntityHttpException();
                }
                $user = User::findOne(['id' => $bid->created_by]);

                if ($bid->save()) {
                    if ($user->email) {
                        Yii::$app->sendMail->run(
                            '@common/views/mail/sendBidStatus-html.php',
                            ['email' => $user->email, 'status' => $bid->status, 'id' => $bid->id],
                            \Yii::$app->params['supportEmail'], $user->email, 'status is change'
                        );
                        $transaction->commit();

                    } elseif ($user->phone_number) {
                        Yii::$app->sendSms->run('Ваша заявка обрела статус' . $bid->status, $user->phone_number);
                        $transaction->commit();
                    }

                    $isAdmin = Yii::$app->user->can(BackendUser::ROLE_ADMIN);
                    $result = [
                        'status'    => 200,
                        'message'   => Yii::t('app', 'Status successfully updated.'),
                        'isAdmin'   => $isAdmin,
                        'bidStatus' => $bid->status,
                    ];
                    if ($isAdmin) {
                        $result['processedStatus'] = BidEntity::getProcessedStatusValue($bid->processed);
                        $result['processedBy'] = Yii::$app->user->identity->profile->name;
                    }
                    return $result;
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            Yii::$app->response->setStatusCode(500);
            return  ['message' => Yii::t('app', 'Bid')];
        }
    }
}
