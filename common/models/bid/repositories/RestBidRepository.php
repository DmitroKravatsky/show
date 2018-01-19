<?php

namespace common\models\bid\repositories;

use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;

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
    public static function getBids($params): ArrayDataProvider
    {
        /** @var ActiveQuery $query */
        $query = self::find()->where(['created_by' => \Yii::$app->user->id]);

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
}