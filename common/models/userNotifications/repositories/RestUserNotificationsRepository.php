<?php

namespace common\models\userNotifications\repositories;

use common\models\userNotifications\NotificationsEntity;
use common\models\userNotifications\UserNotifications;
use yii\data\ArrayDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
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
            $userNotificationsModel = UserNotifications::find()
                ->joinWith('notification')
                ->where(['user_notifications.user_id' => \Yii::$app->user->id]);
            if (isset($params['read'])) {
                $userNotificationsModel->andWhere(['is_read' => $params['read']]);
            }
            $page = isset($params['page']) ? $params['page'] - 1 : 0;

            $dataProvider = new ArrayDataProvider([
                'allModels' => $userNotificationsModel->orderBy(['created_at' => SORT_DESC])->asArray()->all(),
                'pagination' => [
                    'pageSize' => \Yii::$app->request->get('per-page') ?? 10,
                    'page' => $page
                ]
            ]);

            return $dataProvider;

        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }

    }

    /**
     * Removes a notify by Notification id and User id
     *
     * @param $notificationId int
     * @param $userId int
     *
     * @return bool
     *
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteNotify(int $notificationId, int $userId): bool
    {
        $userNotification = UserNotifications::findModel([
                'notification_id' => $notificationId,
                'user_id' => $userId
            ]);
        if ($userNotification->delete()) {
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
    public function findUserNotificationModel(array $params): BaseActiveRecord
    {
        if (empty($userNotificationsModel = UserNotifications::findOne($params))) {
            throw new NotFoundHttpException('Уведомление не найдено.');
        }

        return $userNotificationsModel;
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
     * @param $recipientId int|array
     * @param $customData string
     *
     * @return mixed
     */
    public function addNotify($type, string $text, $recipientId, $customData = null)
    {
        if ($recipientId === null || empty($recipientId)) {
            return true;
        }
        $notification = new NotificationsEntity();
        $notification->setAttributes([
            'type' => $type,
            'text' => $text,
            'custom_data' => $customData
        ]);

        $notification->save();
        $userNotificationRelation = new UserNotifications();

        if (is_array($recipientId)) {
            foreach ($recipientId as $id) {
                $data[] = [
                    'user_id'          => $id,
                    'notification_id ' => $notification->id,
                    'is_read'          => UserNotifications::STATUS_READ_NO,
                    'created_at'       => time(),
                    'updated_at'       => time()
                ];
            }

            return \Yii::$app->db->createCommand()
                ->batchInsert(
                   UserNotifications::tableName(),
                   ['user_id', 'notification_id', 'is_read', 'created_at', 'updated_at'],
                   $data
                )
                ->execute();

        } elseif (is_int($recipientId)) {
            $userNotificationRelation->user_id = $recipientId;
            $userNotificationRelation->notification_id = $notification->id;
            $userNotificationRelation->is_read = 0;

            return $userNotificationRelation->save();
        }
        throw new \InvalidArgumentException('RecipientId must be array or integer');
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
