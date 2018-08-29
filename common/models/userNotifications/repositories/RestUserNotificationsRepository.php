<?php

namespace common\models\userNotifications\repositories;

use common\models\userNotifications\UserNotificationsEntity;
use yii\data\ArrayDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use common\models\userProfile\UserProfileEntity;
use yii\web\ServerErrorHttpException;

/**
 * Class RestUserNotificationsRepository
 * @package common\models\userNotifications\repositories
 */
trait RestUserNotificationsRepository
{
    /**
     * Returns a user by User id
     *
     * @param $params array
     * @return ArrayDataProvider
     *
     * @throws ServerErrorHttpException
     */
    public function getUserNotificationsByUser(array $params): ArrayDataProvider
    {
        try {
            $userNotificationsModel = UserNotificationsEntity::find()
                ->select(['text', 'created_at'])
                ->where(['recipient_id' => \Yii::$app->user->id]);
            if (isset($params['status'])) {
                $userNotificationsModel->andWhere(['status' => $params['status']]);
            }

            $page = isset($params['page']) ? $params['page'] - 1 : 0;

            $dataProvider = new ArrayDataProvider([
                'allModels' => $userNotificationsModel->orderBy(['created_at' => SORT_DESC])->all(),
                'pagination' => [
                    'pageSize' => \Yii::$app->request->get('per-page') ?? 10,
                    'page' => $page
                ]
            ]);

            return $dataProvider;

        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Internal server error');
        }

    }

    /**
     * Removes a notify by Notification id and User id
     *
     * @param $id int
     *
     * @return bool
     *
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteNotify(int $id): bool
    {
        $userNotificationsModel = $this->findModel(['id' => $id, 'recipient_id' => \Yii::$app->user->id]);
        if ($userNotificationsModel->delete()) {
            return true;
        }
        return false;
    }

    /**
     * Finds a Notify by params
     *
     * @param $params array
     *
     * @return BaseActiveRecord
     *
     * @throws NotFoundHttpException
     */
    public function findModel(array $params): BaseActiveRecord
    {
        if (empty($userNotificationsModel = self::findOne($params))) {
            throw new NotFoundHttpException('Уведомление не найдено.');
        }

        return $userNotificationsModel;
    }

    /**
     * Creates a new notify
     *
     * @param $type int
     * @param $text string
     * @param $recipientId int
     * @param $customData string
     *
     * @return mixed
     */
    public function addNotify($type, string $text, int $recipientId, $customData = null)
    {
        $this->setAttributes([
            'type'         => $type,
            'text'         => $text,
            'recipient_id' => $recipientId,
            'custom_data'  => $customData
        ]);


        return $this->save();
    }

    /**
     * Generates a message for a bid with status done
     *
     * @return string
     */
    public static function getMessageForDoneBid(): string
    {
        return 'Your bid is accepted. Transfer to the card {sum} {currency} through the Wallet app. Recipient: Card/account {wallet}.';
    }

    /**
     * Generates a custom data for a bid with status done
     *
     * @param float $sum
     * @param string $currency
     * @param string $wallet
     *
     * @return array
     */
    public static function getCustomDataForDoneBid($sum, $currency, $wallet): array
    {
        return [
            'sum'       => $sum,
            'currency'  => $currency,
            'to_wallet' => $wallet,
        ];
    }

    /**
     * Generates a message for a bid with status rejected
     *
     * @return string
     */
    public static function getMessageForRejectedBid(): string
    {
        return 'Your bid is rejected. Transfer to the card {sum} {currency} through the Wallet app. Recipient: Card/account {wallet}.';
    }

    /**
     * Generates a custom data for a bid with status rejected
     *
     * @param float $sum
     * @param string $currency
     * @param string $wallet
     *
     * @return array
     */
    public static function getCustomDataForRejectedBid($sum, $currency, $wallet): array
    {
        return [
            'sum'      => $sum,
            'currency' => $currency,
            'wallet'   => $wallet
        ];
    }
}