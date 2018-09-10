<?php

namespace common\models\bid;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BidSearch represents the model behind the search form of `common\models\bid\BidEntity`.
 */
class BidSearch extends BidEntity
{
    public $dateRange;

    /**
     * String  of $name and $last_name
     * @var $full_name
     */
    public $full_name;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['processed_by'], 'string'],
            [
                [
                    'full_name', 'phone_number', 'email', 'status', 'from_payment_system',
                    'to_payment_system', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency',
                    'dateRange', 'processed_by', 'processed'
                ],
                'safe'
            ],
            [['from_sum', 'to_sum'], 'number'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
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
        $query = BidEntity::find()->joinWith(['managerProfile'])->with('processedByProfile');

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? Yii::$app->params['pageSize'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'processed'            => $this->processed,
            'user_profile.user_id' => $this->processed_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'from_payment_system', $this->from_wallet])
            ->andFilterWhere(['like', 'to_payment_system', $this->to_wallet])
            ->andFilterWhere([
                'or',
                ['like', 'bid.name', $this->full_name],
                ['like', 'bid.last_name', $this->full_name]
            ]);

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'bid.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
