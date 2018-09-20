<?php

namespace common\models\bid;

use backend\models\BackendUser;
use common\models\userProfile\UserProfileEntity as UserProfile;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

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
            [['id', 'created_by', 'created_at', 'updated_at', 'in_progress_by_manager',], 'integer'],
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

        $expression = 'FIELD ('
            . self::tableName() . '.status,"'
            . self::STATUS_NEW . '","'
            . self::STATUS_IN_PROGRESS . '","'
            . self::STATUS_PAID_BY_CLIENT . '","'
            . self::STATUS_PAID_BY_US_DONE . '","'
            . self::STATUS_REJECTED
        . '")';

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy([new Expression($expression)])
                ->addOrderBy(['created_at' => SORT_DESC]),
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
            'm.id'                 => $this->in_progress_by_manager,
            'bid.status'           => $this->status,
        ])->joinWith([
            'inProgressByManager' => function ($query) {
                $query->from(['m' => BackendUser::tableName()]);
            },
        ])->joinWith([
            'author' => function ($query) {
                $query->from(['author' => BackendUser::tableName()]);
            },
        ]);

        $query->andFilterWhere(['like', 'author.phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'author.email', $this->email])
            ->andFilterWhere(['like', 'from_payment_system', $this->from_wallet])
            ->andFilterWhere(['like', 'to_payment_system', $this->to_wallet])
            ->andFilterWhere([
                'or',
                ['like', UserProfile::tableName() . '.name', $this->full_name],
                ['like', UserProfile::tableName() . '.last_name', $this->full_name]
            ]);

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'bid.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
