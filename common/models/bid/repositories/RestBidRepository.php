<?php

namespace common\models\bid\repositories;

use common\models\bid\BidEntity;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use Yii;
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
        /** @var $bid BidEntity */
        if (empty($bid = self::findOne(['id' => $id, 'created_by' => Yii::$app->user->id]))) {
            throw new NotFoundHttpException('Заявка не найдена.');
        }

        return $bid->getAttributes([
            'status', 'from_payment_system', 'to_payment_system', 'from_wallet', 'to_wallet', 'from_currency',
            'to_currency', 'from_sum', 'to_sum'
        ]);
    }
}