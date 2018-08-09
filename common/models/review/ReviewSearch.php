<?php

namespace common\models\review;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\review\ReviewEntity;

/**
 * ReviewSearch represents the model behind the search form of `common\models\review\ReviewEntity`.
 */
class ReviewSearch extends ReviewEntity
{
    public $dateRange;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['text', 'dateRange',], 'safe'],
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
        $query = ReviewEntity::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
