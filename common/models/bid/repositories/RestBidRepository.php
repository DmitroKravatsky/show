<?php

namespace common\models\bid\repositories;

use common\components\SendSms;
use common\models\bid\BidEntity;
use common\models\paymentSystem\PaymentSystem;
use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\data\ArrayDataProvider;
use yii\web\ErrorHandler;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use Yii;

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
     * @param boolean $excepted
     *
     * @return ArrayDataProvider
     */
    public function getBids(array $params, $excepted): ArrayDataProvider
    {
        $query = BidEntity::find()
            ->joinWith('fromPaymentSystem as from_payment_system')
            ->joinWith('toPaymentSystem as to_payment_system')
            ->select([
                self::tableName() . '.id', 'status', 'from_payment_system.name as from_payment_system',
                'from_payment_system_id', 'to_payment_system_id',
                'to_payment_system.name as to_payment_system',
                'from_payment_system.currency as from_currency', 'to_payment_system.currency as to_currency',
                'from_sum', 'to_sum'
            ])->where(['created_by' => \Yii::$app->user->id]);

        if ((bool) $excepted) {
            $query->andWhere(['status' => [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_PAID_BY_CLIENT]]);
        } else {
            $query->andWhere(['status' => [self::STATUS_PAID_BY_US_DONE, self::STATUS_REJECTED]]);
        }

        if (isset($params['sort']) && $params['sort'] === self::SORT_WEEK) {
            $query->andWhere(['>=', self::tableName() . '.created_at', time() - (self::SECONDS_IN_WEEK)]);
        } elseif (isset($params['sort']) && $params['sort'] === self::SORT_MONTH) {
            $query->andWhere(['>=', self::tableName() . '.created_at', time() - (self::SECONDS_IN_MONTH)]);
        }

        $bids = $query->orderBy([self::tableName() . '.created_at' => SORT_DESC])->all();

        $result = [];
        foreach ($bids as $bid) {
            /** @var BidEntity $bid */
            $result[] = [
                'id'                  => $bid->id,
                'status'              => BidEntity::getStatusValue($bid->status),
                'from_payment_system' => $bid->fromPaymentSystem->name,
                'to_payment_system'   => $bid->toPaymentSystem->name,
                'from_currency'       => PaymentSystem::getCurrencyValue($bid->fromPaymentSystem->currency),
                'to_currency'         => PaymentSystem::getCurrencyValue($bid->toPaymentSystem->currency),
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
            $bid = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);

            return [
                'id'                  => $bid->id,
                'status'              => static::getStatusValue($bid->status),
                'from_payment_system' => $bid->fromPaymentSystem->name,
                'to_payment_system'   => $bid->toPaymentSystem->name,
                'from_wallet'         => $bid->from_wallet,
                'to_wallet'           => $bid->to_wallet,
                'from_currency'       => PaymentSystem::getCurrencyValue($bid->fromPaymentSystem->currency),
                'to_currency'         => PaymentSystem::getCurrencyValue($bid->toPaymentSystem->currency),
                'from_sum'            => round($bid->from_sum, 2),
                'to_sum'              => round($bid->to_sum, 2),
            ];

        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
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
     * @return array

     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function createBid(array $postData): array
    {
        $bid = new BidEntity();
        $bid->setScenario(BidEntity::SCENARIO_CREATE);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $bid->setAttributes($postData);
            if (!$bid->validate()) {
                $this->throwModelException($bid->errors);
            }
            if (!$bid->save(false)) {
                throw new ServerErrorHttpException();
            }
            if (Yii::$app->user->can(RestUserEntity::ROLE_GUEST)) {
                $this->createOrUpdateUserByBid($bid);
            }
            $transaction->commit();

            return [
                'id'                  => $bid->id,
                'created_by'          => $bid->created_by,
                'name'                => $bid->author->profile->name,
                'last_name'           => $bid->author->profile->last_name,
                'phone_number'        => $bid->author->phone_number,
                'email'               => $bid->author->email,
                'status'              => $bid->status,
                'from_payment_system' => $bid->fromPaymentSystem->name,
                'to_payment_system'   => $bid->toPaymentSystem->name,
                'from_currency'       => PaymentSystem::getCurrencyValue($bid->fromPaymentSystem->currency),
                'to_currency'         => PaymentSystem::getCurrencyValue($bid->toPaymentSystem->currency),
                'from_sum'            => round($bid->from_sum, 2),
                'to_sum'              => round($bid->to_sum, 2),
                'crated_at'           => $bid->created_at,
            ];
        } catch (UnprocessableEntityHttpException $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * @param BidEntity $bid
     * @throws \yii\base\Exception
     */
    protected function createOrUpdateUserByBid(BidEntity $bid)
    {
        if (($user = RestUserEntity::findByEmail($bid->email)) || ($user = RestUserEntity::findByPhoneNumber($bid->phone_number))) {
            if ($user->email != $bid->email) {
                $user->email = $bid->email;
                if (!$user->save(true, ['email'])) {
                    $this->throwModelException($user->errors);
                }
            }
            if ($user->phone_number != $bid->phone_number) {
                $user->phone_number = $bid->phone_number;
                if (!$user->save(true, ['phone_number'])) {
                    $this->throwModelException($user->errors);
                }
            }

            $profile = $user->profile;
            if ($profile->name != $bid->name) {
                $profile->name = $bid->name;
            }
            if ($profile->last_name != $bid->last_name) {
                $profile->last_name = $bid->last_name;
            }
            if (!$profile->save(true, ['name', 'last_name'])) {
                $this->throwModelException($profile->errors);
            }
            return;
        }


        $userAttributes = ['email' => $bid->email, 'phone_number' => $bid->phone_number];
        $password = Yii::$app->security->generateRandomString(12);
        $user = new RestUserEntity(['scenario' => RestUserEntity::SCENARIO_REGISTER_BY_BID]);
        $userAttributes = array_merge($userAttributes, [
            'password' => $password,
            'register_by_bid' => RestUserEntity::REGISTER_BY_BID_YES,
        ]);
        $user->setAttributes($userAttributes);
        if (!$user->validate()) {
            $this->throwModelException($user->errors);
        }
        $user->save(false);

        $user->refresh_token = $user->getRefreshToken(['user_id' => $user->id]);
        $user->created_refresh_token = time();
        $user->save(false, ['refresh_token', 'created_refresh_token']);

        $profileAttributes = ['name' => $bid->name, 'last_name' => $bid->last_name, 'user_id' => $user->id];
        $profile = new UserProfileEntity();
        $profile->setAttributes($profileAttributes);
        if (!$profile->validate()) {
            $this->throwModelException($profile->errors);
        }
        $profile->save(false);

        /** @var SendSms $smsComponent */
        $smsComponent = Yii::$app->sendSms;
        //$smsComponent->run($this->getMessageForRegistrationByPhoneNumber($user, $password), $user->phone_number);
    }

    /**
     * @param RestUserEntity $user
     * @param string $password
     * @return string
     */
    protected function getMessageForRegistrationByPhoneNumber(RestUserEntity $user, $password): string
    {
        $message = <<<MES
Уважаемы клиент, {$user->getFullName()}, была произведена регистрация.
Ваш логин: {$user->phone_number}.
Ваш пароль: {$password}.
MES;

        return $message;
    }

    /**
     * Finds a Bid model by params
     *
     * @param $params array
     *
     * @return BidEntity
     *
     * @throws NotFoundHttpException if there is no such bid
     */
    protected function findModel(array $params): BidEntity
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
                    'from_payment_system' => $params->fromPaymentSystem->name,
                    'to_payment_system'   => $params->toPaymentSystem->name,
                    'from_currency'       => PaymentSystem::getCurrencyValue($params->fromPaymentSystem->currency),
                    'to_currency'         => PaymentSystem::getCurrencyValue($params->toPaymentSystem->currency),
                ],
                \Yii::$app->params['supportEmail'], $recipients, 'New Bid'
            );
            return true;
        }
    }
}
