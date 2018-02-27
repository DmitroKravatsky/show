<?php

namespace common\models\bid\repositories;

use common\models\bid\BidEntity;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use Yii;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Class RestBidRepository
 * @package common\models\bid\repositories
 */
trait RestBidRepository
{
    /**
     * @param $params
     * @return ArrayDataProvider
     */
    public function getBids($params): ArrayDataProvider
    {
        /** @var ActiveQuery $query */
        $query = self::find()->where(['created_by' => Yii::$app->user->id]);

        if (isset($params['created_at']) && $params['created_at'] == 'week') {
            $query->andWhere(['>=', 'created_at', time() - (3600 * 24 * 7)]);
        } elseif (isset($params['created_at']) && $params['created_at'] == 'month') {
            $query->andWhere(['>=', 'created_at', time() - (3600 * 24 * 30)]);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $query->orderBy(['created_at' => SORT_DESC])->all(),
            'pagination' => [
                'pageSize' => $params['per-page'] ?? 10
            ]
        ]);

        return $dataProvider;
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function getBidDetails($id)
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        
        return $bid->getAttributes([
            'status', 'from_payment_system', 'to_payment_system', 'from_wallet', 'to_wallet', 'from_currency',
            'to_currency', 'from_sum', 'to_sum'
        ]);
    }

    /**
     * @param $id
     * @return BidEntity
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateBid($id): BidEntity
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        $bid->setScenario(BidEntity::SCENARIO_UPDATE);
        $bid->setAttributes(Yii::$app->request->bodyParams);

        if (!$bid->save()) {
            $this->throwModelException($bid->errors);
        }

        return $bid;
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteBid($id): bool 
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        if ($bid->delete()) {
           return true;
        }
        return false;
    }

    /**
     * @return BidEntity
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createBid(): BidEntity
    {
        $bid = new self;
        $bid->setScenario(BidEntity::SCENARIO_CREATE);
        $bid->setAttributes(Yii::$app->request->bodyParams);

        if (!$bid->save()) {
            $this->throwModelException($bid->errors);
        }

        return $bid;
    }

    /**
     * @param $params
     * @return BaseActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($params): BaseActiveRecord
    {
        if (empty($bidModel = self::findOne($params))) {
            throw new NotFoundHttpException('Заявка не найдена');
        }

        return $bidModel;
    }
}