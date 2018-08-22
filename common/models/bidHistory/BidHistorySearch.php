<?php

namespace common\models\bidHistory;

use common\models\userProfile\UserProfileEntity;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BidHistorySearch represents the model behind the search form of `common\models\bidHistory\BidHistory`.
 */
class BidHistorySearch extends BidHistory
{
    public $time_range;
    public $created_by;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'bid_id', 'time',], 'integer'],
            [['status', 'time_range', 'processed_by', 'created_by',], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
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
        $query = BidHistory::find()->leftJoin(UserProfileEntity::tableName(), 'user_id = processed_by');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere([
                'or',
                ['like', 'user_profile.name', $this->processed_by],
                ['like', 'user_profile.last_name', $this->processed_by]
            ])->andFilterWhere([
                'or',
                ['like', 'user_profile.name', $this->created_by],
                ['like', 'user_profile.last_name', $this->created_by]
            ]);

        if (!empty($this->time_range) && strpos($this->time_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->time_range);
            $query->andFilterWhere(['between', 'time', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
