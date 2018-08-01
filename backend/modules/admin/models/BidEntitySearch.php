<?php

namespace backend\modules\admin\models;

use common\models\bid\BidEntity;
use Yii;
use common\models\user\User;
use yii\data\ActiveDataProvider;

/**
 * RestEmployeeEntitySearch represents the model behind the search form of `rest\modules\api\v1\employee\models\RestEmployeeEntity`.
 */
class BidEntitySearch extends BidEntity
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                [
                    'id', 'from_currency', 'to_currency',
                    'from_sum', 'to_sum'
                ],
                'integer'
            ],
            [['created_by', 'status', 'from_payment_system', 'to_payment_system', 'from_wallet', 'to_wallet'], 'string'],
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BidEntity::find()->select(['id', 'status', 'created_by', 'from_payment_system',
            'to_payment_system', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency',
            'from_sum', 'to_sum', 'created_at', 'updated_at']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'created_by' => $this->created_by,
            /*'from_payment_system' => $this->from_payment_system,
            'to_payment_system' => $this->to_payment_system,
            'from_wallet' => $this->from_wallet,
            'to_wallet' => $this->to_payment_system,
            'from_currency' => $this->from_currency,
            'to_currency' => $this->to_currency,
            'from_sum' => $this->from_sum,
            'to_sum' => $this->to_sum,*/
        ]);

        $query->andFilterWhere(['like', 'id', $this->id]);
        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
