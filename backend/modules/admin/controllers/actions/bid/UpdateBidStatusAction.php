<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\models\BackendUser;
use backend\modules\admin\controllers\BidController;
use common\models\bid\BidEntity as Bid;
use common\models\bid\BidEntity;
use common\models\user\User;
use yii\{ base\Action, helpers\Html, web\UnprocessableEntityHttpException, web\Response };
use Yii;

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
        Yii::$app->language = $bodyParams['language'];

        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $bid = $this->controller->findBid($id);

            $bid->setScenario($bid::SCENARIO_UPDATE_BID_STATUS);
            $bid->status = $newStatus;
            if (($newStatus == Bid::STATUS_PAID_BY_US_DONE) || ($newStatus == Bid::STATUS_REJECTED)) {
                $bid->processed = Bid::PROCESSED_YES;
                $bid->processed_by = Yii::$app->user->id;
                $bid->in_progress_by_manager = null;
            } elseif ($newStatus == Bid::STATUS_IN_PROGRESS) {
                $bid->processed = Bid::PROCESSED_NO;
                $bid->processed_by = null;
                $bid->in_progress_by_manager = Yii::$app->user->id;
            }

            if (!$bid->validate()) {
                \Yii::$app->response->setStatusCode(422);
                throw new UnprocessableEntityHttpException();
            }
            $user = User::findOne(['id' => $bid->created_by]);

            $bidOldStatus = $bid->getOldAttribute('status');
            if ($bid->save()) {
                if ($user->email) {
                    Yii::$app->sendMail->run(
                        '@common/views/mail/sendBidStatus-html.php',
                        ['email' => $user->email, 'status' => $bid->status, 'id' => $bid->id],
                        \Yii::$app->params['supportEmail'], $user->email, 'status is change'
                    );
                    $transaction->commit();

                } elseif ($user->phone_number) {
                    //Yii::$app->sendSms->run('Ваша заявка обрела статус' . $bid->status, $user->phone_number);
                    $transaction->commit();
                }

                $noSetMsg = Html::tag('span', Yii::t('yii', '(not set)'), ['class' => 'not-set']);
                $processedBy = Yii::$app->user->identity->profile->name;
                $inProgressByManager = $noSetMsg;
                if ($bid->status == Bid::STATUS_IN_PROGRESS) {
                    $processedBy = $noSetMsg;
                    $inProgressByManager = Yii::$app->user->identity->profile->name;
                }

                return [
                    'status'              => 200,
                    'message'             => Yii::t('app', 'Status successfully updated.'),
                    'isAdmin'             => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                    'bidStatusValue'      => $bid->status,
                    'bidStatusText'       => BidEntity::getStatusValue($bid->status),
                    'processedStatus'     => Bid::getProcessedStatusValue($bid->processed),
                    'processedBy'         => $processedBy,
                    'inProgressByManager' => $inProgressByManager,
                    'bidOldStatusValue'   => $bidOldStatus,
                    'bidOldStatusText'    => Bid::getStatusValue($bidOldStatus),
                ];
            }
            Yii::$app->response->setStatusCode(500);
            return  ['message' => Yii::t('app', 'Something wrong, please try again later.')];
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            Yii::$app->response->setStatusCode(500);
            return  ['message' => Yii::t('app', 'Something wrong, please try again later.')];
        }
    }
}
