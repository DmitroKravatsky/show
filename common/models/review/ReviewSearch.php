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
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['text', 'dateRange', 'created_by',], 'safe'],
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
        $query = ReviewEntity::find()->joinWith('createdByProfile');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? Yii::$app->params['pageSize'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere([
                'or',
                ['like', 'user_profile.name', $this->created_by],
                ['like', 'user_profile.last_name', $this->created_by]
            ]);

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'review.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
