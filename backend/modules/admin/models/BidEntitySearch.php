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
                    'from_sum', 'to_sum', 'created_at', 'updated_at'
                ],
                'integer'
            ],
            [
                [
                    'created_by', 'status', 'name', 'from_payment_system', 'to_payment_system',
                    'from_wallet', 'to_wallet', 'last_name', 'email', 'phone_number'
                ],
                'string'
            ],
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
        $query = BidEntity::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'created_by' => $this->created_by,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);
        $query->andFilterWhere(['like', 'phone_number', $this->phone_number]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'last_name', $this->last_name]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
