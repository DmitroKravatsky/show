<?php

namespace common\models\bid\repositories;

use common\models\bid\BidEntity;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class RestBidRepository
 * @package common\models\bid\repositories
 */
trait RestBidRepository
{
    /**
     * Method of getting user's bids by Bid id and User id
     *
     * @param $params array of the POST data
     *
     * @return ArrayDataProvider
     *
     * @throws ServerErrorHttpException
     */
    public function getBids(array $params): ArrayDataProvider
    {
        try {
            /** @var ActiveQuery $query */
            $query = self::find()->where(['created_by' => \Yii::$app->user->id]);

            if (isset($params['created_at']) && $params['created_at'] === 'week') {
                $query->andWhere(['>=', 'created_at', time() - (3600 * 24 * 7)]);
            } elseif (isset($params['created_at']) && $params['created_at'] === 'month') {
                $query->andWhere(['>=', 'created_at', time() - (3600 * 24 * 30)]);
            }

            $pageSize = intval($params['per-page'] ?? \Yii::$app->params['posts-per-page']);
            $page = intval(isset($params['page']) ? $params['page'] - 1 : 0);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $query->orderBy(['created_at' => SORT_DESC])->all(),
                'pagination' => [
                    'pageSize' => $pageSize,
                    'page' => $page
                ]
            ]);

            return $dataProvider;

        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Internal server error');
        }
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

            return $bid->getAttributes([
                'id', 'status', 'from_payment_system', 'to_payment_system', 'from_wallet', 'to_wallet',
                'from_currency', 'to_currency', 'from_sum', 'to_sum'
            ]);
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
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createBid(array $postData): BidEntity
    {
        $bid = new self;
        $bid->setScenario(BidEntity::SCENARIO_CREATE);
        $bid->setAttributes($postData);

        if (!$bid->save()) {
            $this->throwModelException($bid->errors);
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

    public function updateBidStatus()
    {

    }
}