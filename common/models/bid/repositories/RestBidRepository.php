<?php

namespace common\models\bid\repositories;

use common\models\bid\BidEntity;
use yii\data\ArrayDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class RestBidRepository
 * @package common\models\bid\repositories
 */
trait RestBidRepository
{
    /**
     * Method of getting user's bids by User id
     *
     * @param $params array of the POST data
     *
     * @return ArrayDataProvider
     */
    public function getBids(array $params): ArrayDataProvider
    {
        $query = BidEntity::find()
            ->select([
                'id', 'status', 'from_payment_system', 'to_payment_system',
                'from_currency', 'to_currency', 'from_sum', 'to_sum'
            ])->where(['created_by' => \Yii::$app->user->id]);

        if (isset($params['sort']) && $params['sort'] === self::SORT_WEEK) {
            $query->andWhere(['>=', 'created_at', time() - (self::SECONDS_IN_WEEK)]);
        } elseif (isset($params['sort']) && $params['sort'] === self::SORT_MONTH) {
            $query->andWhere(['>=', 'created_at', time() - (self::SECONDS_IN_MONTH)]);
        }

        $bids = $query->orderBy(['created_at' => SORT_DESC])->all();

        $result = [];
        foreach ($bids as $bid) {
            /** @var BidEntity $bid */
            $result[] = [
                'id'                  => $bid->id,
                'status'              => BidEntity::getStatusValue($bid->status),
                'from_payment_system' => BidEntity::getPaymentSystemValue($bid->from_payment_system),
                'to_payment_system'   => BidEntity::getPaymentSystemValue($bid->to_payment_system),
                'from_currency'       => BidEntity::getCurrencyValue($bid->from_currency),
                'to_currency'         => BidEntity::getCurrencyValue($bid->to_currency),
                'from_sum'            => round($bid->from_sum, 2),
                'to_sum'              => round($bid->to_sum, 2),
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => $params['per-page'] ?? \Yii::$app->params['posts-per-page'],
                'page' => isset($params['page']) ? $params['page'] - 1 : 0,
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Get a bid's detail by Bid id and User id
     *
     * @param $id
     *
     * @return array
     *
     * @throws NotFoundHttpException if there is no such bid
     * @throws ServerErrorHttpException if there is no such bid
     */
    public function getBidDetails($id)
    {
        try{
            $bid = $this->findModel(['id' => $id, 'created_by' => \Yii::$app->user->id]);

            $attributes = $bid->getAttributes([
                'id', 'status', 'from_payment_system', 'to_payment_system', 'from_wallet', 'to_wallet',
                'from_currency', 'to_currency', 'from_sum', 'to_sum'
            ]);

            $attributes['status'] = static::getStatusValue($attributes['status']);
            $attributes['from_payment_system'] = static::getPaymentSystemValue($attributes['from_payment_system']);
            $attributes['to_payment_system'] = static::getPaymentSystemValue($attributes['to_payment_system']);
            $attributes['from_currency'] = static::getCurrencyValue($attributes['from_currency']);
            $attributes['to_currency'] = static::getCurrencyValue($attributes['to_currency']);
            $attributes['from_sum'] = round($attributes['from_sum'], 2);
            $attributes['to_sum'] = round($attributes['to_sum'], 2);

            return $attributes;

        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Server error occurred , please try later');
        }

    }

    /**
     * Updates User's bid by Bid id and User id
     *
     * @param $id int
     * @param $postData array of the POST data
     *
     * @return BidEntity
     *
     * @throws NotFoundHttpException if there is no such bid
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateBid(int $id, array $postData): BidEntity
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => \Yii::$app->user->id]);
        $bid->setScenario(BidEntity::SCENARIO_UPDATE);
        $bid->setAttributes($postData);

        if (!$bid->save()) {
            $this->throwModelException($bid->errors);
        }

        return $bid;
    }

    /**
     * Removes a user's bid by Bid id and User id
     *
     * @param $id int
     *
     * @return bool
     *
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteBid(int $id): bool
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => \Yii::$app->user->id]);
        if ($bid->delete()) {
           return true;
        }
        return false;
    }

    /**
     * Add new bid to db with the set of income data
     *
     * @param $postData array of the POST data
     *
     * @return BidEntity whether the attributes are valid and the record is inserted successfully
     *
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function createBid(array $postData): BidEntity
    {
        $bid = new BidEntity();
        $bid->setScenario(BidEntity::SCENARIO_CREATE);
        $bid->setAttributes($postData);

        if (!$bid->validate()) {
            $this->throwModelException($bid->errors);
        }

        if (!$bid->save(false)) {
            throw new ServerErrorHttpException();
        }

        return $bid;
    }

    /**
     * Finds a Bid model by params
     *
     * @param $params array
     *
     * @return BaseActiveRecord
     *
     * @throws NotFoundHttpException if there is no such bid
     */
    protected function findModel(array $params): BaseActiveRecord
    {
        if (empty($bidModel = self::findOne($params))) {
            throw new NotFoundHttpException('Bid is not found');
        }

        return $bidModel;
    }

    /**
     * Sends letters to managers
     * @param BidEntity $params
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function sendEmailToManagers(BidEntity $params):bool
    {
        $query = new \yii\db\Query();

        $managers = $query->select(['auth_assignment.user_id', 'user.id', 'user.email'])
            ->from('auth_assignment')
            ->leftJoin('user', 'user.id=auth_assignment.user_id')
            ->where(['auth_assignment.item_name' => ['admin', 'manager']])
            ->all();

        foreach ($managers as $manager) {
            if ($manager['email']) {
                $recipients[] = $manager['email'];
            }
        }

        if ($recipients) {
            \Yii::$app->sendMail->run(
                '@common/views/mail/sendBidInfo-html.php',
                [
                    'id' => $params->created_by,
                    'email' => $params->email ?? 'не установлено',
                    'name' => $params->name,
                    'phone_number' => $params->phone_number,
                    'last_name' => $params->last_name,
                    'from_sum' => $params->from_sum,
                    'to_sum' => $params->to_sum,
                    'from_wallet' => $params->from_wallet,
                    'to_wallet' => $params->to_wallet,
                    'from_payment_system' => $params->from_payment_system,
                    'to_payment_system' => $params->to_payment_system,
                    'from_currency' => $params->from_currency,
                    'to_currency' => $params->to_currency,
                ],
                \Yii::$app->params['supportEmail'], $recipients, 'New Bid'
            );
            return true;
        }
    }
}